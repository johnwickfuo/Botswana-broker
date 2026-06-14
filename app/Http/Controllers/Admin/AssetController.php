<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use App\Models\AssetDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AssetController extends Controller
{
    /**
     * Allowed upload types and per-file size cap (10 MB) for official documents.
     */
    private const FILE_RULES = 'file|mimes:pdf,jpg,jpeg,png|max:10240';

    /**
     * List all assets.
     */
    public function index()
    {
        $assets = Asset::withCount(['certificates', 'supportingDocuments'])
            ->orderByDesc('id')
            ->paginate(15);

        return view('admin.assets.index', compact('assets'));
    }

    /**
     * Show the create form.
     */
    public function create()
    {
        return view('admin.assets.create');
    }

    /**
     * Store a new asset with its certificates / supporting documents.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'           => 'required|string|max:255',
            'category'       => 'required|string|max:255',
            'description'    => 'nullable|string',
            'status'         => 'required|in:active,inactive',
            'certificates'   => 'nullable|array',
            'certificates.*' => self::FILE_RULES,
            'documents'      => 'nullable|array',
            'documents.*'    => self::FILE_RULES,
        ]);

        $asset = Asset::create([
            'name'        => $data['name'],
            'category'    => $data['category'],
            'description' => $data['description'] ?? null,
            'status'      => $data['status'],
        ]);

        $this->storeUploads($asset, $request->file('certificates', []), AssetDocument::TYPE_CERTIFICATE);
        $this->storeUploads($asset, $request->file('documents', []), AssetDocument::TYPE_SUPPORTING);

        return redirect()->route('admin.assets.index')
            ->with('success', 'Asset created successfully.');
    }

    /**
     * Show the edit form.
     */
    public function edit($asset)
    {
        $asset = Asset::with('documents')->findOrFail($asset);

        return view('admin.assets.edit', compact('asset'));
    }

    /**
     * Update an asset and optionally append new documents.
     */
    public function update(Request $request, $asset)
    {
        $asset = Asset::findOrFail($asset);

        $data = $request->validate([
            'name'           => 'required|string|max:255',
            'category'       => 'required|string|max:255',
            'description'    => 'nullable|string',
            'status'         => 'required|in:active,inactive',
            'certificates'   => 'nullable|array',
            'certificates.*' => self::FILE_RULES,
            'documents'      => 'nullable|array',
            'documents.*'    => self::FILE_RULES,
        ]);

        $asset->update([
            'name'        => $data['name'],
            'category'    => $data['category'],
            'description' => $data['description'] ?? null,
            'status'      => $data['status'],
        ]);

        $this->storeUploads($asset, $request->file('certificates', []), AssetDocument::TYPE_CERTIFICATE);
        $this->storeUploads($asset, $request->file('documents', []), AssetDocument::TYPE_SUPPORTING);

        return redirect()->route('admin.assets.edit', $asset->id)
            ->with('success', 'Asset updated successfully.');
    }

    /**
     * Toggle active/inactive.
     */
    public function toggleStatus($asset)
    {
        $asset = Asset::findOrFail($asset);
        $asset->status = $asset->status === Asset::STATUS_ACTIVE
            ? Asset::STATUS_INACTIVE
            : Asset::STATUS_ACTIVE;
        $asset->save();

        return redirect()->back()
            ->with('success', "Asset marked as {$asset->status}.");
    }

    /**
     * Delete an asset, its document rows and their files.
     */
    public function destroy($asset)
    {
        $asset = Asset::with('documents')->findOrFail($asset);

        foreach ($asset->documents as $document) {
            $this->deleteFile($document->path);
        }

        // asset_documents rows are removed via the FK cascade.
        $asset->delete();

        return redirect()->route('admin.assets.index')
            ->with('success', 'Asset deleted successfully.');
    }

    /**
     * Delete a single document (file + row).
     */
    public function destroyDocument($document)
    {
        $document = AssetDocument::findOrFail($document);
        $this->deleteFile($document->path);
        $document->delete();

        return redirect()->back()
            ->with('success', 'Document removed successfully.');
    }

    /**
     * Securely stream a stored document to the admin (never a public URL).
     */
    public function downloadDocument($document)
    {
        $document = AssetDocument::findOrFail($document);

        abort_unless(Storage::disk(AssetDocument::DISK)->exists($document->path), 404);

        return Storage::disk(AssetDocument::DISK)->download(
            $document->path,
            $document->original_name
        );
    }

    /**
     * Persist uploaded files to the private disk and record their metadata.
     *
     * @param  \Illuminate\Http\UploadedFile[]  $files
     */
    private function storeUploads(Asset $asset, $files, string $type): void
    {
        foreach (array_filter((array) $files) as $file) {
            $path = $file->store("asset_documents/{$asset->id}", AssetDocument::DISK);

            $asset->documents()->create([
                'type'          => $type,
                'path'          => $path,
                'original_name' => $file->getClientOriginalName(),
                'mime'          => $file->getClientMimeType(),
                'size'          => $file->getSize(),
            ]);
        }
    }

    private function deleteFile(?string $path): void
    {
        if ($path && Storage::disk(AssetDocument::DISK)->exists($path)) {
            Storage::disk(AssetDocument::DISK)->delete($path);
        }
    }
}
