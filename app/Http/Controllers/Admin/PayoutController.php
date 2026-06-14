<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PlanPayout;
use App\Models\UserPlan;
use App\Services\PayoutService;
use Illuminate\Support\Carbon;

/**
 * Phase 6 — admin payout management: review payouts due, trigger processing,
 * and force a specific investment to maturity.
 */
class PayoutController extends Controller
{
    public function __construct(private PayoutService $payouts)
    {
    }

    public function index()
    {
        $now = Carbon::now();

        $duePayouts = PlanPayout::with(['userPlan.investmentPlan', 'user'])
            ->where('status', PlanPayout::STATUS_PENDING)
            ->where('due_date', '<=', $now)
            ->orderBy('due_date')
            ->get();

        $activeInvestments = UserPlan::with(['investmentPlan', 'investor'])
            ->where('status', 'active')
            ->orderBy('maturity_date')
            ->paginate(20);

        $dueTotal = (float) $duePayouts->sum('amount');

        return view('admin.payouts.index', compact('duePayouts', 'activeInvestments', 'dueTotal'));
    }

    /**
     * Process all payouts currently due (the manual equivalent of the
     * scheduled job).
     */
    public function processDue()
    {
        $summary = $this->payouts->processDue();

        return redirect()->route('admin.payouts.index')->with(
            'success',
            sprintf(
                '%d payout(s) credited (total %.2f); %d investment(s) matured.',
                $summary['payouts'],
                $summary['credited'],
                $summary['matured']
            )
        );
    }

    /**
     * Force a single investment to maturity (credit all remaining payouts).
     */
    public function markMatured(UserPlan $investment)
    {
        if ($investment->status !== 'active') {
            return redirect()->back()->with('error', 'That investment is not active.');
        }

        $summary = $this->payouts->forceMature($investment);

        return redirect()->back()->with(
            'success',
            sprintf('Investment matured — %d payout(s) credited (total %.2f).', $summary['payouts'], $summary['credited'])
        );
    }
}
