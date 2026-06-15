<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AssetDocument;
use App\Services\ReturnCalculator;
use App\Support\Money;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use InvalidArgumentException;

/**
 * Public, guest-accessible investor pages. Citizens invest directly into
 * ASSETS, each carrying its own terms. Lists active assets, shows asset detail
 * with viewable government certificates, and serves the live return quote
 * (which IS the backend ReturnCalculator, so the on-page figure always matches
 * the server).
 */
class PublicPlanController extends Controller
{
    public function __construct(private ReturnCalculator $calculator)
    {
    }

    /**
     * Public listing of all active, investable assets.
     */
    public function index()
    {
        $assets = Asset::where('status', Asset::STATUS_ACTIVE)
            ->whereNotNull('amount_type')
            ->withCount('certificates')
            ->orderByDesc('id')
            ->get();

        return view('invest.index', compact('assets'));
    }

    /**
     * Public asset detail: terms, certificates and the live calculator.
     */
    public function show(Asset $asset)
    {
        abort_unless($asset->amount_type && $asset->status === Asset::STATUS_ACTIVE, 404);

        $asset->load(['certificates']);

        $initial = $this->safeCalculate(
            $asset,
            $asset->amount_type === Asset::AMOUNT_RANGED ? (float) $asset->min_amount : null
        );

        return view('invest.show', compact('asset', 'initial'));
    }

    /**
     * Live return quote — the single source of truth for the on-page figure.
     */
    public function quote(Asset $asset, Request $request)
    {
        abort_unless($asset->amount_type && $asset->status === Asset::STATUS_ACTIVE, 404);

        $amount = $request->filled('amount') ? (float) $request->input('amount') : null;

        try {
            $result = $this->calculator->calculate($asset, $amount);
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

    private function safeCalculate(Asset $asset, ?float $amount): ?array
    {
        try {
            return $this->calculator->calculate($asset, $amount);
        } catch (\Throwable $e) {
            return null;
        }
    }
}
