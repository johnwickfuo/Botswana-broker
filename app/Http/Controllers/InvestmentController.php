<?php

namespace App\Http\Controllers;

use App\Exceptions\InvestmentException;
use App\Models\Asset;
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
     * Invest in an asset from the wallet balance.
     */
    public function store(Request $request, Asset $asset)
    {
        $data = $request->validate([
            'amount'        => 'nullable|numeric|min:0',
            'accept_terms'  => 'accepted',
        ]);

        try {
            $this->service->invest(
                Auth::user(),
                $asset,
                $request->filled('amount') ? (float) $data['amount'] : null,
                $request->boolean('accept_terms')
            );
        } catch (InvestmentException $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }

        return redirect()->route('investments.mine')
            ->with('success', 'Investment confirmed. ' . $asset->name . ' is now active until maturity.');
    }

    /**
     * The citizen's investments.
     */
    public function myInvestments()
    {
        $investments = UserPlan::where('user_id', Auth::id())
            ->with(['investmentAsset', 'payouts'])
            ->orderByDesc('id')
            ->paginate(15);

        $mine = UserPlan::where('user_id', Auth::id());
        $active = (clone $mine)->where('status', 'active');

        $summary = [
            'invested'     => (float) (clone $active)->sum('invested_amount'),
            'expected'     => (float) (clone $active)->sum('expected_return'),
            'earned'       => (float) (clone $mine)->sum('total_profit'),
            'active_count' => (clone $active)->count(),
        ];

        return view('invest.my-investments', compact('investments', 'summary'));
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
