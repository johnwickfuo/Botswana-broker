<?php

namespace App\Services;

use App\Exceptions\InvestmentException;
use App\Models\Asset;
use App\Models\AuditLog;
use App\Models\Settings;
use App\Models\User;
use App\Models\UserPlan;
use App\Models\WalletTransaction;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

/**
 * Invest / redeem domain logic. Citizens invest directly into ASSETS, each of
 * which carries its own terms (amount limits, return, duration, payout
 * frequency). Kept free of HTTP concerns so it can be driven in tests.
 */
class InvestmentService
{
    public function __construct(
        private ReturnCalculator $calculator,
        private PayoutService $payouts
    ) {
    }

    /**
     * Invest a citizen's wallet balance into an asset.
     *
     * @throws InvestmentException  on any business-rule violation
     */
    public function invest(User $user, Asset $asset, ?float $amount, bool $acceptTerms): UserPlan
    {
        if ($asset->status !== Asset::STATUS_ACTIVE || !$asset->amount_type) {
            throw new InvestmentException('This asset is not available for investment.');
        }

        $this->assertKycApproved($user);

        if (!$acceptTerms) {
            throw new InvestmentException('You must accept the investment terms to proceed.');
        }

        if (!$asset->isOfferOpen()) {
            throw new InvestmentException('This asset is not currently open for investment.');
        }

        // Resolve principal + expected return (also validates fixed/ranged limits).
        try {
            $breakdown = $this->calculator->calculate($asset, $amount);
        } catch (InvalidArgumentException $e) {
            throw new InvestmentException($e->getMessage());
        }
        $principal = $breakdown['principal'];
        $expectedReturn = $breakdown['return'];

        $remaining = $asset->remainingCapacity();
        if ($remaining !== null && $principal > $remaining) {
            throw new InvestmentException('This asset does not have enough remaining capacity for that amount.');
        }

        if ((float) $user->account_bal < $principal) {
            throw new InvestmentException('Insufficient wallet balance for this investment.');
        }

        return DB::transaction(function () use ($user, $asset, $principal, $expectedReturn) {
            $start = Carbon::now();
            $maturity = $this->maturityDate($asset, $start);

            // Deduct from the wallet.
            $user->account_bal = (float) $user->account_bal - $principal;
            $user->save();

            $investment = UserPlan::create([
                'user_id'           => $user->id,
                'asset_id'          => $asset->id,
                'invested_amount'   => $principal,
                'current_value'     => $principal,
                'expected_return'   => $expectedReturn,
                'roi_percentage'    => $asset->return_type === 'percentage' ? $asset->return_percentage : null,
                'total_profit'      => 0,
                'status'            => 'active',
                'start_date'        => $start,
                'maturity_date'     => $maturity,
                'activated_at'      => $start,
                'expires_at'        => $maturity,
                'locked'            => true,
                'terms_accepted_at' => $start,
                'payment_method'    => 'wallet',
            ]);

            $this->recordLedger(
                $user,
                WalletTransaction::TYPE_DEBIT,
                $principal,
                "Investment in {$asset->name}",
                $investment
            );

            // Build the periodic return + maturity payout schedule.
            $this->payouts->schedule($investment);

            AuditLog::record(
                'investment.invested',
                $investment,
                "Invested {$principal} in asset \"{$asset->name}\"",
                ['amount' => $principal, 'expected_return' => $expectedReturn, 'asset_id' => $asset->id]
            );

            return $investment;
        });
    }

    /**
     * Redeem a matured investment — credits any outstanding payouts (remaining
     * return slices + principal) to the wallet. Early redemption is rejected.
     *
     * @throws InvestmentException
     */
    public function redeem(UserPlan $investment, ?Carbon $now = null): UserPlan
    {
        if ($investment->status !== 'active') {
            throw new InvestmentException('This investment is not active and cannot be redeemed.');
        }

        if (!$investment->isMatured($now)) {
            $date = optional($investment->maturity_date)->format('d M Y');
            throw new InvestmentException(
                "This investment is locked until maturity ({$date}). Early redemption is not permitted."
            );
        }

        $this->payouts->forceMature($investment);

        AuditLog::record(
            'investment.redeemed',
            $investment,
            "Redeemed investment #{$investment->id}",
            ['investment_id' => $investment->id]
        );

        return $investment->fresh();
    }

    private function assertKycApproved(User $user): void
    {
        $settings = Settings::find(1);
        $kycRequired = $settings && $settings->enable_kyc_registration === 'yes';

        if ($kycRequired && $user->account_verify !== 'Verified') {
            throw new InvestmentException('Please complete KYC verification before investing.');
        }
    }

    private function maturityDate(Asset $asset, Carbon $start): Carbon
    {
        $units = (int) ($asset->duration ?? 0);

        return match ($asset->duration_type) {
            'weeks'  => $start->copy()->addWeeks($units),
            'months' => $start->copy()->addMonths($units),
            'years'  => $start->copy()->addYears($units),
            default  => $start->copy()->addDays($units),
        };
    }

    private function recordLedger(User $user, string $type, float $amount, string $description, UserPlan $investment): void
    {
        WalletTransaction::create([
            'user_id'        => $user->id,
            'type'           => $type,
            'amount'         => $amount,
            'balance_after'  => (float) $user->account_bal,
            'description'    => $description,
            'reference_type' => UserPlan::class,
            'reference_id'   => $investment->id,
        ]);
    }
}
