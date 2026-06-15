<?php

namespace Tests\Feature;

use App\Models\AuditLog;
use App\Models\PlanPayout;
use App\Models\UserPlan;
use App\Services\InvestmentService;
use App\Services\PayoutService;
use Illuminate\Support\Carbon;

/**
 * Phase 9 — payout scheduling, periodic distributions and maturity handling.
 */
class PayoutMaturityTest extends InvestmentTestCase
{
    private function invest(float $balance = 10000, float $amount = 1000)
    {
        $user = $this->makeUser($balance);
        // 90 days, monthly payouts, 12% => 3 return slices of 40 + principal.
        $plan = $this->makeAsset(['duration' => 90, 'payout_interval' => 'monthly', 'return_percentage' => 12, 'min_amount' => 100]);
        $investment = app(InvestmentService::class)->invest($user, $plan, $amount, true);

        return [$user, $investment];
    }

    public function test_schedule_is_generated_on_invest(): void
    {
        [$user, $investment] = $this->invest();

        $payouts = PlanPayout::where('user_plan_id', $investment->id)->get();
        $this->assertSame(3, $payouts->where('type', 'return')->count());
        $this->assertSame(1, $payouts->where('type', 'principal')->count());
        $this->assertSame(120.0, round((float) $payouts->where('type', 'return')->sum('amount'), 2));
        $this->assertSame(1000.0, (float) $payouts->firstWhere('type', 'principal')->amount);
    }

    public function test_interim_payouts_credit_returns_while_active(): void
    {
        [$user, $investment] = $this->invest();
        $payouts = app(PayoutService::class);

        $payouts->processDue(Carbon::now()->addDays(30)->addMinute());
        $user->refresh();
        $investment->refresh();

        $this->assertSame(9040.0, (float) $user->account_bal); // 9000 + one 40 slice
        $this->assertSame(40.0, (float) $investment->total_profit);
        $this->assertSame('active', $investment->status);
    }

    public function test_maturity_credits_principal_and_marks_matured(): void
    {
        [$user, $investment] = $this->invest();
        $payouts = app(PayoutService::class);

        $payouts->processDue(Carbon::now()->addDays(90)->addMinute());
        $user->refresh();
        $investment->refresh();

        // 9000 + 120 returns + 1000 principal = 10120 (net = +120 over original 10000)
        $this->assertSame(10120.0, (float) $user->account_bal);
        $this->assertSame('matured', $investment->status);
        $this->assertFalse($investment->locked);
        $this->assertSame(1, AuditLog::where('action', 'investment.matured')->count());
    }

    public function test_processing_is_idempotent(): void
    {
        [$user, $investment] = $this->invest();
        $payouts = app(PayoutService::class);

        $payouts->processDue(Carbon::now()->addDays(120));
        $balance = (float) $user->fresh()->account_bal;

        $payouts->processDue(Carbon::now()->addDays(200)); // run again
        $this->assertSame($balance, (float) $user->fresh()->account_bal);
        $this->assertSame(0, PlanPayout::where('user_plan_id', $investment->id)->where('status', 'pending')->count());
    }
}
