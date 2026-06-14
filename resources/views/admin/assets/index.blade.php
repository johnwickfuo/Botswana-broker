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

                <div class="mt-2 mb-4 d-flex justify-content-between align-items-center">
                    <h1 class="title1 text-{{ $text }}">Assets</h1>
                    <a href="{{ route('admin.assets.create') }}" class="btn btn-primary">
                        <i class="fa fa-plus"></i> Add Asset
                    </a>
                </div>

                @include('admin.assets.partials.flash')

                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped mt-3">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Category</th>
                                        <th>Status</th>
                                        <th>Certificates</th>
                                        <th>Supporting</th>
                                        <th class="text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($assets as $asset)
                                        <tr>
                                            <td>{{ $asset->id }}</td>
                                            <td>{{ $asset->name }}</td>
                                            <td>{{ $asset->category }}</td>
                                            <td>
                                                @if ($asset->status === \App\Models\Asset::STATUS_ACTIVE)
                                                    <span class="badge badge-success">Active</span>
                                                @else
                                                    <span class="badge badge-secondary">Inactive</span>
                                                @endif
                                            </td>
                                            <td>{{ $asset->certificates_count }}</td>
                                            <td>{{ $asset->supporting_documents_count }}</td>
                                            <td class="text-right">
                                                <a href="{{ route('admin.assets.edit', $asset->id) }}"
                                                    class="btn btn-sm btn-info">
                                                    <i class="fa fa-edit"></i>
                                                </a>

                                                <form action="{{ route('admin.assets.toggle', $asset->id) }}"
                                                    method="post" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-warning">
                                                        {{ $asset->status === \App\Models\Asset::STATUS_ACTIVE ? 'Deactivate' : 'Activate' }}
                                                    </button>
                                                </form>

                                                <form action="{{ route('admin.assets.destroy', $asset->id) }}"
                                                    method="post" class="d-inline"
                                                    onsubmit="return confirm('Delete this asset and all its documents?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted py-4">
                                                No assets yet. Click <strong>Add Asset</strong> to create one.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-3">
                            {{ $assets->links() }}
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
