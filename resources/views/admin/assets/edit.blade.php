<?php
if (Auth('admin')->User()->dashboard_style == 'light') {
    $text = 'dark';
} else {
    $text = 'light';
}
?>
@extends('layouts.app')
@section('content')
    @include('admin.topmenu')
    @include('admin.sidebar')
    <div class="main-panel">
        <div class="content">
            <div class="page-inner">

                <p>
                    <a href="{{ route('admin.assets.index') }}">
                        <i class="p-2 rounded-lg fa fa-arrow-circle-left fa-2x bg-light"></i>
                    </a>
                </p>

                <div class="mt-2 mb-4">
                    <h1 class="title1 text-{{ $text }}">Edit Asset</h1>
                </div>

                @include('admin.assets.partials.flash')

                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.assets.update', $asset->id) }}" method="post"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            @include('admin.assets.partials.form', ['asset' => $asset])

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-save"></i> Update Asset
                                </button>
                                <a href="{{ route('admin.assets.index') }}" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-header">
                        <h4 class="card-title">Uploaded Documents</h4>
                    </div>
                    <div class="card-body">
                        @if ($asset->documents->isEmpty())
                            <p class="text-muted mb-0">No documents uploaded yet.</p>
                        @else
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>File</th>
                                            <th>Type</th>
                                            <th>Size</th>
                                            <th class="text-right">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($asset->documents as $document)
                                            <tr>
                                                <td>{{ $document->original_name ?? basename($document->path) }}</td>
                                                <td>
                                                    @if ($document->type === \App\Models\AssetDocument::TYPE_CERTIFICATE)
                                                        <span class="badge badge-info">Certificate</span>
                                                    @else
                                                        <span class="badge badge-secondary">Supporting</span>
                                                    @endif
                                                </td>
                                                <td>{{ $document->readable_size }}</td>
                                                <td class="text-right">
                                                    <a href="{{ route('admin.assets.document.download', $document->id) }}"
                                                        class="btn btn-sm btn-success">
                                                        <i class="fa fa-download"></i> Download
                                                    </a>
                                                    <form
                                                        action="{{ route('admin.assets.document.destroy', $document->id) }}"
                                                        method="post" class="d-inline"
                                                        onsubmit="return confirm('Remove this document?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
