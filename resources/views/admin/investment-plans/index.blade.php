<?php
$text = Auth('admin')->User()->dashboard_style == 'light' ? 'dark' : 'light';
?>
@extends('layouts.app')
@section('content')
    @include('admin.topmenu')
    @include('admin.sidebar')
    <div class="main-panel">
        <div class="content">
            <div class="page-inner">

                <div class="mt-2 mb-4 d-flex justify-content-between align-items-center">
                    <h1 class="title1 text-{{ $text }}">Investment Plans</h1>
                    <a href="{{ route('admin.investment-plans.create') }}" class="btn btn-primary">
                        <i class="fa fa-plus"></i> New Plan
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
                                        <th>Asset</th>
                                        <th>Amount</th>
                                        <th>Return</th>
                                        <th>Status</th>
                                        <th class="text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($plans as $plan)
                                        <tr>
                                            <td>{{ $plan->id }}</td>
                                            <td>{{ $plan->name }}</td>
                                            <td>{{ optional($plan->asset)->name ?? '—' }}</td>
                                            <td>
                                                @if ($plan->amount_type === 'fixed')
                                                    Fixed: {{ number_format($plan->fixed_amount, 2) }}
                                                @else
                                                    {{ number_format($plan->min_amount, 2) }} – {{ number_format($plan->max_amount, 2) }}
                                                @endif
                                            </td>
                                            <td>
                                                @if ($plan->return_type === 'percentage')
                                                    {{ rtrim(rtrim(number_format($plan->return_percentage, 2), '0'), '.') }}%
                                                @else
                                                    Fixed: {{ number_format($plan->fixed_return, 2) }}
                                                @endif
                                            </td>
                                            <td>
                                                @if ($plan->active)
                                                    <span class="badge badge-success">Active</span>
                                                @else
                                                    <span class="badge badge-secondary">Inactive</span>
                                                @endif
                                            </td>
                                            <td class="text-right">
                                                <a href="{{ route('admin.investment-plans.edit', $plan->id) }}"
                                                    class="btn btn-sm btn-info"><i class="fa fa-edit"></i></a>
                                                <form action="{{ route('admin.investment-plans.destroy', $plan->id) }}"
                                                    method="post" class="d-inline"
                                                    onsubmit="return confirm('Delete this plan?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted py-4">
                                                No investment plans yet. Click <strong>New Plan</strong> to build one.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3">{{ $plans->links() }}</div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
