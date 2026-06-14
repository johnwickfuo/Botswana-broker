<?php

namespace App\Http\Controllers;

use App\Models\AssetDocument;
use App\Models\Plan;
use App\Services\ReturnCalculator;
use App\Support\Money;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use InvalidArgumentException;

/**
 * Phase 4 — public, guest-accessible investor pages for the Republic of
 * Botswana investment platform. Lists active investment plans, shows full
 * plan + asset detail with viewable government certificates, and serves the
 * live return quote (which IS the backend ReturnCalculator, so the on-page
 * figure always matches the server).
 */
class PublicPlanController extends Controller
{
    public function __construct(private ReturnCalculator $calculator)
    {
    }

    /**
     * Public listing of all active investment plans + their asset summary.
     */
    public function index()
    {
        $plans = Plan::where('active', true)
            ->whereNotNull('amount_type')
            ->with('asset')
            ->orderBy('sort_order')
            ->orderByDesc('id')
            ->get();

        return view('invest.index', compact('plans'));
    }

    /**
     * Public plan detail: full terms, linked asset details + certificates,
     * and the live calculator.
     */
    public function show(Plan $plan)
    {
        abort_unless($plan->amount_type && $plan->active, 404);

        $plan->load(['asset.certificates']);

        // Server-computed seed so the page is correct before any JS runs.
        $initial = $this->safeCalculate(
            $plan,
            $plan->amount_type === Plan::AMOUNT_RANGED ? (float) $plan->min_amount : null
        );

        return view('invest.show', compact('plan', 'initial'));
    }

    /**
     * Live return quote — the single source of truth for the on-page figure.
     * Returns the ReturnCalculator result plus Pula-formatted strings.
     */
    public function quote(Plan $plan, Request $request)
    {
        abort_unless($plan->amount_type && $plan->active, 404);

        $amount = $request->filled('amount') ? (float) $request->input('amount') : null;

        try {
            $result = $this->calculator->calculate($plan, $amount);
        } catch (InvalidArgumentException $e) {
            return response()->json(['ok' => false, 'message' => $e->getMessage()], 422);
        }

        return response()->json([
            'ok'                  => true,
            'principal'           => $result['principal'],
            'return'              => $result['return'],
            'total'               => $result['total'],
            'principal_formatted' => Money::pula($result['principal']),
            'return_formatted'    => Money::pula($result['return']),
            'total_formatted'     => Money::pula($result['total']),
        ]);
    }

    /**
     * Publicly view a government CERTIFICATE inline. Supporting documents are
     * deliberately NOT exposed here (admin-only).
     */
    public function certificate(AssetDocument $document)
    {
        abort_unless($document->type === AssetDocument::TYPE_CERTIFICATE, 404);
        abort_unless(Storage::disk(AssetDocument::DISK)->exists($document->path), 404);

        return Storage::disk(AssetDocument::DISK)->response(
            $document->path,
            $document->original_name,
            ['Content-Disposition' => 'inline; filename="' . $document->original_name . '"']
        );
    }

    private function safeCalculate(Plan $plan, ?float $amount): ?array
    {
        try {
            return $this->calculator->calculate($plan, $amount);
        } catch (\Throwable $e) {
            return null;
        }
    }
}
