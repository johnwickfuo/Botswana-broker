<?php

namespace App\Http\Controllers;

use App\Exceptions\InvestmentException;
use App\Models\Plan;
use App\Models\UserPlan;
use App\Services\InvestmentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Phase 5 — citizen-facing invest / redeem actions. Thin wrapper around
 * InvestmentService; all the rules live in the service.
 */
class InvestmentController extends Controller
{
    public function __construct(private InvestmentService $service)
    {
    }

    /**
     * Invest in a plan from the wallet balance.
     */
    public function store(Request $request, Plan $plan)
    {
        $data = $request->validate([
            'amount'        => 'nullable|numeric|min:0',
            'accept_terms'  => 'accepted',
        ]);

        try {
            $investment = $this->service->invest(
                Auth::user(),
                $plan,
                $request->filled('amount') ? (float) $data['amount'] : null,
                $request->boolean('accept_terms')
            );
        } catch (InvestmentException $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }

        return redirect()->route('investments.mine')
            ->with('success', 'Investment confirmed. ' . $plan->name . ' is now active until maturity.');
    }

    /**
     * The citizen's investments.
     */
    public function myInvestments()
    {
        $investments = UserPlan::where('user_id', Auth::id())
            ->with('investmentPlan.asset')
            ->orderByDesc('id')
            ->paginate(15);

        return view('invest.my-investments', compact('investments'));
    }

    /**
     * Redeem a matured investment (blocked before maturity).
     */
    public function redeem(UserPlan $investment)
    {
        abort_unless($investment->user_id === Auth::id(), 403);

        try {
            $this->service->redeem($investment);
        } catch (InvestmentException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->route('investments.mine')
            ->with('success', 'Investment redeemed — principal and return credited to your wallet.');
    }
}
