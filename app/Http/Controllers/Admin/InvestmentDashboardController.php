<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\PlanPayout;
use App\Models\UserPlan;
use Illuminate\Support\Carbon;

/**
 * Phase 7 — investment back-office dashboard: total raised (overall / per
 * asset / per plan), active investment count & value, and payouts due.
 */
class InvestmentDashboardController extends Controller
{
    public function index()
    {
        $now = Carbon::now();

        // Headline figures.
        $totalRaised   = (float) UserPlan::whereIn('status', ['active', 'matured'])->sum('invested_amount');
        $activeCount   = UserPlan::where('status', 'active')->count();
        $activeValue   = (float) UserPlan::where('status', 'active')->sum('invested_amount');
        $maturedCount  = UserPlan::where('status', 'matured')->count();
        $returnsPaid   = (float) PlanPayout::where('type', PlanPayout::TYPE_RETURN)
            ->where('status', PlanPayout::STATUS_PROCESSED)->sum('amount');

        $duePayouts    = PlanPayout::where('status', PlanPayout::STATUS_PENDING)->where('due_date', '<=', $now);
        $payoutsDueCount = (clone $duePayouts)->count();
        $payoutsDueTotal = (float) (clone $duePayouts)->sum('amount');

        // Raised per plan (active + matured).
        $perPlan = UserPlan::whereIn('status', ['active', 'matured'])
            ->selectRaw('plan_id, COUNT(*) as investors, SUM(invested_amount) as raised')
            ->groupBy('plan_id')
            ->get();

        $plans = Plan::with('asset')->whereIn('id', $perPlan->pluck('plan_id'))->get()->keyBy('id');

        // Raised per asset (aggregated from per-plan).
        $perAsset = [];
        foreach ($perPlan as $row) {
            $plan = $plans->get($row->plan_id);
            $assetName = optional(optional($plan)->asset)->name ?? 'Unlinked';
            $perAsset[$assetName] = ($perAsset[$assetName] ?? 0) + (float) $row->raised;
        }
        arsort($perAsset);

        return view('admin.investment-dashboard.index', compact(
            'totalRaised', 'activeCount', 'activeValue', 'maturedCount', 'returnsPaid',
            'payoutsDueCount', 'payoutsDueTotal', 'perPlan', 'plans', 'perAsset'
        ));
    }
}
