<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;

/**
 * Phase 3 — listing + create/edit shells for the spec-compliant investment
 * plans. The conditional create/edit form itself is the Livewire PlanBuilder
 * component embedded in the form view. Only plans created through the builder
 * (those carrying an amount_type) are listed here, leaving the legacy plan
 * screens untouched.
 */
class InvestmentPlanController extends Controller
{
    public function index()
    {
        $plans = Plan::whereNotNull('amount_type')
            ->with('asset')
            ->orderByDesc('id')
            ->paginate(15);

        return view('admin.investment-plans.index', compact('plans'))->with('title','Investment Plans');
    }

    public function create()
    {
        return view('admin.investment-plans.form', ['planId' => null, 'title' => 'New Plan']);
    }

    public function edit(Plan $plan)
    {
        return view('admin.investment-plans.form', ['planId' => $plan->id, 'title' => 'Edit Plan']);
    }

    public function destroy(Plan $plan)
    {
        $plan->delete();

        return redirect()->route('admin.investment-plans.index')
            ->with('success', 'Investment plan deleted.');
    }
}
