<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use App\Models\PlanPayout;
use App\Models\UserPlan;
use Illuminate\Support\Carbon;

/**
 * Investment back-office dashboard: total raised (overall / per asset), active
 * investment count & value, and payouts due. Citizens invest into assets.
 */
class InvestmentDashboardController extends Controller
{
    public function index()
    {
        $now = Carbon::now();

        $totalRaised   = (float) UserPlan::whereIn('status', ['active', 'matured'])->sum('invested_amount');
        $activeCount   = UserPlan::where('status', 'active')->count();
        $activeValue   = (float) UserPlan::where('status', 'active')->sum('invested_amount');
        $maturedCount  = UserPlan::where('status', 'matured')->count();
        $returnsPaid   = (float) PlanPayout::where('type', PlanPayout::TYPE_RETURN)
            ->where('status', PlanPayout::STATUS_PROCESSED)->sum('amount');

        $duePayouts      = PlanPayout::where('status', PlanPayout::STATUS_PENDING)->where('due_date', '<=', $now);
        $payoutsDueCount = (clone $duePayouts)->count();
        $payoutsDueTotal = (float) (clone $duePayouts)->sum('amount');

        // Raised per asset (active + matured).
        $assetRows = UserPlan::whereIn('status', ['active', 'matured'])
            ->whereNotNull('asset_id')
            ->selectRaw('asset_id, COUNT(*) as investors, SUM(invested_amount) as raised')
            ->groupBy('asset_id')
            ->get();

        $assets = Asset::whereIn('id', $assetRows->pluck('asset_id'))->get()->keyBy('id');

        return view('admin.investment-dashboard.index', compact(
            'totalRaised', 'activeCount', 'activeValue', 'maturedCount', 'returnsPaid',
            'payoutsDueCount', 'payoutsDueTotal', 'assetRows', 'assets'
        ))->with('title', 'Investment Dashboard');
    }
}
