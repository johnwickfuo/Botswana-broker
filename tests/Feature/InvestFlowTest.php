<?php

namespace Tests\Feature;

use App\Exceptions\InvestmentException;
use App\Models\AuditLog;
use App\Models\UserPlan;
use App\Models\WalletTransaction;
use App\Services\InvestmentService;
use Illuminate\Support\Carbon;

/**
 * Phase 9 — invest flow: wallet debit, record creation, ledger, guards,
 * maturity lock and audit trail.
 */
class InvestFlowTest extends InvestmentTestCase
{
    private function service(): InvestmentService
    {
        return app(InvestmentService::class);
    }

    public function test_kyc_citizen_can_invest_and_everything_is_recorded(): void
    {
        $user = $this->makeUser(10000);
        $plan = $this->makePlan(['name' => 'Diamond Growth', 'return_percentage' => 10]);

        $investment = $this->service()->invest($user, $plan, 2000, true);
        $user->refresh();

        $this->assertSame(8000.0, (float) $user->account_bal, 'wallet debited');
        $this->assertSame('active', $investment->status);
        $this->assertTrue($investment->locked);
        $this->assertSame(2000.0, (float) $investment->invested_amount);
        $this->assertSame(200.0, (float) $investment->expected_return);
        $this->assertNotNull($investment->maturity_date);

        $debit = WalletTransaction::where('reference_id', $investment->id)
            ->where('type', 'debit')->first();
        $this->assertNotNull($debit);
        $this->assertSame(2000.0, (float) $debit->amount);

        $this->assertSame(1, AuditLog::where('action', 'investment.invested')->count());
    }

    public function test_guards_reject_invalid_investments(): void
    {
        $plan = $this->makePlan();

        $this->assertRejected(fn () => $this->service()->invest($this->makeUser(100), $this->makePlan(['amount_type' => 'fixed', 'fixed_amount' => 50000, 'return_type' => 'fixed', 'fixed_return' => 100]), null, true));
        $this->assertRejected(fn () => $this->service()->invest($this->makeUser(10000), $plan, 100, true));     // below min
        $this->assertRejected(fn () => $this->service()->invest($this->makeUser(10000), $plan, 99999, true));   // above max
        $this->assertRejected(fn () => $this->service()->invest($this->makeUser(10000), $plan, 2000, false));   // terms
        $this->assertRejected(fn () => $this->service()->invest($this->makeUser(10000, 'Pending'), $plan, 2000, true)); // KYC
    }

    public function test_offer_window_and_capacity_are_enforced(): void
    {
        $this->assertRejected(fn () => $this->service()->invest($this->makeUser(10000), $this->makePlan(['offer_starts_at' => Carbon::now()->addDay()]), 2000, true));
        $this->assertRejected(fn () => $this->service()->invest($this->makeUser(10000), $this->makePlan(['offer_ends_at' => Carbon::now()->subDay()]), 2000, true));

        $capUser = $this->makeUser(100000);
        $capPlan = $this->makePlan(['capacity_amount' => 3000]);
        $this->service()->invest($capUser, $capPlan, 2000, true);
        $this->assertSame(1000.0, $capPlan->fresh()->remainingCapacity());
        $this->assertRejected(fn () => $this->service()->invest($capUser, $capPlan->fresh(), 2000, true));
    }

    public function test_no_early_redemption_then_payout_at_maturity(): void
    {
        $user = $this->makeUser(10000);
        $plan = $this->makePlan(['return_percentage' => 10]);
        $investment = $this->service()->invest($user, $plan, 2000, true);

        $this->assertRejected(fn () => $this->service()->redeem($investment));

        $investment->maturity_date = Carbon::now()->subDay();
        $investment->save();
        $this->service()->redeem($investment->fresh());

        $user->refresh();
        $this->assertSame('matured', UserPlan::find($investment->id)->status);
        $this->assertSame(10200.0, (float) $user->account_bal); // principal 2000 + return 200 back
        $this->assertSame(1, AuditLog::where('action', 'investment.redeemed')->count());
    }

    private function assertRejected(callable $fn): void
    {
        try {
            $fn();
            $this->fail('Expected InvestmentException was not thrown.');
        } catch (InvestmentException $e) {
            $this->assertTrue(true);
        }
    }
}
