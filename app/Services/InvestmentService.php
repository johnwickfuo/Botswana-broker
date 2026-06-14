<?php

namespace App\Services;

use App\Exceptions\InvestmentException;
use App\Models\Plan;
use App\Models\Settings;
use App\Models\User;
use App\Models\UserPlan;
use App\Models\WalletTransaction;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

/**
 * Phase 5 — the invest/redeem domain logic for the Republic of Botswana
 * investment platform. Kept free of HTTP concerns so it can be driven
 * end-to-end in tests.
 */
class InvestmentService
{
    public function __construct(
        private ReturnCalculator $calculator,
        private PayoutService $payouts
    ) {
    }

    /**
     * Invest a citizen's wallet balance into a plan.
     *
     * @throws InvestmentException  on any business-rule violation
     */
    public function invest(User $user, Plan $plan, ?float $amount, bool $acceptTerms): UserPlan
    {
        if (!$plan->amount_type || !$plan->active) {
            throw new InvestmentException('This plan is not available for investment.');
        }

        $this->assertKycApproved($user);

        if (!$acceptTerms) {
            throw new InvestmentException('You must accept the investment terms to proceed.');
        }

        if (!$plan->isOfferOpen()) {
            throw new InvestmentException('This plan is not currently open for investment.');
        }

        // Resolve principal + expected return (also validates fixed/ranged limits).
        try {
            $breakdown = $this->calculator->calculate($plan, $amount);
        } catch (InvalidArgumentException $e) {
            throw new InvestmentException($e->getMessage());
        }
        $principal = $breakdown['principal'];
        $expectedReturn = $breakdown['return'];

        $remaining = $plan->remainingCapacity();
        if ($remaining !== null && $principal > $remaining) {
            throw new InvestmentException(
                'This plan does not have enough remaining capacity for that amount.'
            );
        }

        if ((float) $user->account_bal < $principal) {
            throw new InvestmentException('Insufficient wallet balance for this investment.');
        }

        return DB::transaction(function () use ($user, $plan, $principal, $expectedReturn) {
            $start = Carbon::now();
            $maturity = $this->maturityDate($plan, $start);

            // Deduct from the wallet.
            $user->account_bal = (float) $user->account_bal - $principal;
            $user->save();

            $investment = UserPlan::create([
                'user_id'           => $user->id,
                'plan_id'           => $plan->id,
                'invested_amount'   => $principal,
                'current_value'     => $principal,
                'expected_return'   => $expectedReturn,
                'roi_percentage'    => $plan->return_type === 'percentage' ? $plan->return_percentage : null,
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
                "Investment in {$plan->name}",
                $investment
            );

            // Build the periodic return + maturity payout schedule.
            $this->payouts->schedule($investment);

            return $investment;
        });
    }

    /**
     * Redeem a matured investment — credits any outstanding payouts (remaining
     * return slices + principal) to the wallet via the PayoutService. Early
     * redemption is rejected (locked until maturity).
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

    private function maturityDate(Plan $plan, Carbon $start): Carbon
    {
        $units = (int) ($plan->duration ?? 0);

        return match ($plan->duration_type) {
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
