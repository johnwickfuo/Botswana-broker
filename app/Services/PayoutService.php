<?php

namespace App\Services;

use App\Models\Asset;
use App\Models\AuditLog;
use App\Models\PlanPayout;
use App\Models\User;
use App\Models\UserPlan;
use App\Models\WalletTransaction;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Phase 6 — payout scheduling, periodic return distributions and maturity
 * handling for citizen investments.
 *
 * Model: the expected return is split into N slices, one per payout_interval
 * over the term, each credited to the wallet when due. The principal is repaid
 * at maturity, at which point the investment is marked matured.
 */
class PayoutService
{
    private const INTERVAL_DAYS = [
        'daily'   => 1,
        'weekly'  => 7,
        'monthly' => 30,
    ];

    /**
     * Build the payout schedule for a freshly-created investment.
     */
    public function schedule(UserPlan $investment): void
    {
        $asset = $investment->investmentAsset()->first();
        if (!$asset) {
            return;
        }

        $start = $investment->start_date ? Carbon::parse($investment->start_date) : Carbon::now();
        $maturity = $investment->maturity_date ? Carbon::parse($investment->maturity_date) : $start;

        $expectedReturn = (float) $investment->expected_return;
        $periods = $this->periods($asset);
        $intervalDays = $this->intervalDays($asset);

        // Return slices, one per interval (rounding remainder lands on the last).
        $sliceBase = round($expectedReturn / $periods, 2);
        $distributed = 0.0;
        for ($k = 1; $k <= $periods; $k++) {
            $amount = $k === $periods ? round($expectedReturn - $distributed, 2) : $sliceBase;
            $distributed += $amount;

            PlanPayout::create([
                'user_plan_id'   => $investment->id,
                'user_id'        => $investment->user_id,
                'amount'         => $amount,
                'roi_percentage' => $investment->roi_percentage,
                'type'           => PlanPayout::TYPE_RETURN,
                'status'         => PlanPayout::STATUS_PENDING,
                'due_date'       => $start->copy()->addDays($k * $intervalDays),
            ]);
        }

        // Principal repayment at maturity.
        PlanPayout::create([
            'user_plan_id' => $investment->id,
            'user_id'      => $investment->user_id,
            'amount'       => (float) $investment->invested_amount,
            'type'         => PlanPayout::TYPE_PRINCIPAL,
            'status'       => PlanPayout::STATUS_PENDING,
            'due_date'     => $maturity,
        ]);
    }

    /**
     * Process every pending payout that is now due, across all active
     * investments (the scheduled-job entry point).
     *
     * @return array{payouts: int, credited: float, matured: int}
     */
    public function processDue(?Carbon $now = null): array
    {
        $now = $now ?: Carbon::now();

        $investmentIds = PlanPayout::where('status', PlanPayout::STATUS_PENDING)
            ->where('due_date', '<=', $now)
            ->distinct()
            ->pluck('user_plan_id');

        $summary = ['payouts' => 0, 'credited' => 0.0, 'matured' => 0];

        foreach ($investmentIds as $id) {
            $investment = UserPlan::find($id);
            if (!$investment || $investment->status !== 'active') {
                continue;
            }
            $result = $this->processInvestment($investment, $now, false);
            $summary['payouts'] += $result['payouts'];
            $summary['credited'] += $result['credited'];
            $summary['matured'] += $result['matured'];
        }

        return $summary;
    }

    /**
     * Force-process ALL remaining payouts for an investment immediately
     * (citizen redemption at maturity / admin "mark matured").
     */
    public function forceMature(UserPlan $investment): array
    {
        return $this->processInvestment($investment, Carbon::now(), true);
    }

    /**
     * Credit the due (or all, when $forceAll) pending payouts for one
     * investment, accruing earned return and marking it matured once the
     * principal is repaid.
     *
     * @return array{payouts: int, credited: float, matured: int}
     */
    public function processInvestment(UserPlan $investment, ?Carbon $now = null, bool $forceAll = false): array
    {
        $now = $now ?: Carbon::now();

        return DB::transaction(function () use ($investment, $now, $forceAll) {
            $query = PlanPayout::where('user_plan_id', $investment->id)
                ->where('status', PlanPayout::STATUS_PENDING)
                ->orderBy('due_date')
                ->orderByRaw("CASE WHEN type = 'principal' THEN 1 ELSE 0 END"); // principal last

            if (!$forceAll) {
                $query->where('due_date', '<=', $now);
            }

            $payouts = $query->get();
            if ($payouts->isEmpty()) {
                return ['payouts' => 0, 'credited' => 0.0, 'matured' => 0];
            }

            $user = User::find($investment->user_id);
            $credited = 0.0;
            $matured = 0;

            foreach ($payouts as $payout) {
                $amount = (float) $payout->amount;

                $user->account_bal = (float) $user->account_bal + $amount;
                $user->save();

                $payout->status = PlanPayout::STATUS_PROCESSED;
                $payout->processed_at = $now;
                $payout->save();

                $isPrincipal = $payout->type === PlanPayout::TYPE_PRINCIPAL;

                WalletTransaction::create([
                    'user_id'        => $user->id,
                    'type'           => WalletTransaction::TYPE_CREDIT,
                    'amount'         => $amount,
                    'balance_after'  => (float) $user->account_bal,
                    'description'    => ($isPrincipal ? 'Principal repayment' : 'Return payout')
                        . ' — ' . optional($investment->investmentAsset()->first())->name,
                    'reference_type' => PlanPayout::class,
                    'reference_id'   => $payout->id,
                ]);

                if ($isPrincipal) {
                    $investment->status = 'matured';
                    $investment->locked = false;
                    $matured = 1;
                } else {
                    $investment->total_profit = (float) $investment->total_profit + $amount;
                    $investment->last_payout_at = $now;
                }

                $credited += $amount;
            }

            $investment->current_value = (float) $investment->invested_amount + (float) $investment->total_profit;
            $investment->save();

            if ($matured) {
                AuditLog::record(
                    'investment.matured',
                    $investment,
                    "Investment #{$investment->id} matured; principal repaid",
                    ['credited' => round($credited, 2)]
                );
            }

            return ['payouts' => $payouts->count(), 'credited' => round($credited, 2), 'matured' => $matured];
        });
    }

    private function intervalDays(Asset $asset): int
    {
        return self::INTERVAL_DAYS[$asset->payout_interval] ?? max(1, $this->durationDays($asset));
    }

    private function durationDays(Asset $asset): int
    {
        return max(1, (int) $asset->getDurationInDays());
    }

    private function periods(Asset $asset): int
    {
        return max(1, intdiv($this->durationDays($asset), $this->intervalDays($asset)));
    }
}
