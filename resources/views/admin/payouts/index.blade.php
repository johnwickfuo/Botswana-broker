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
                    <h1 class="title1 text-{{ $text }}">Payout Management</h1>
                    <form action="{{ route('admin.payouts.process-due') }}" method="post"
                        onsubmit="return confirm('Process all payouts that are currently due?');">
                        @csrf
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-play"></i> Process all due now
                        </button>
                    </form>
                </div>

                @include('admin.assets.partials.flash')

                {{-- Payouts currently due --}}
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">Payouts Due ({{ $duePayouts->count() }})</h4>
                        <span class="text-muted">Total due: {{ number_format($dueTotal, 2) }}</span>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Plan</th>
                                        <th>Citizen</th>
                                        <th>Type</th>
                                        <th>Amount</th>
                                        <th>Due</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($duePayouts as $payout)
                                        <tr>
                                            <td>{{ optional(optional($payout->userPlan)->investmentPlan)->name ?? '—' }}</td>
                                            <td>{{ optional($payout->user)->name ?? 'User #' . $payout->user_id }}</td>
                                            <td>
                                                @if ($payout->type === 'principal')
                                                    <span class="badge badge-dark">Principal</span>
                                                @else
                                                    <span class="badge badge-info">Return</span>
                                                @endif
                                            </td>
                                            <td>{{ number_format($payout->amount, 2) }}</td>
                                            <td>{{ optional($payout->due_date)->format('d M Y') }}</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="5" class="text-center text-muted py-3">No payouts are currently due.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Active investments --}}
                <div class="card mt-4">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Active Investments</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Plan</th>
                                        <th>Citizen</th>
                                        <th>Principal</th>
                                        <th>Earned</th>
                                        <th>Maturity</th>
                                        <th class="text-right">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($activeInvestments as $investment)
                                        <tr>
                                            <td>{{ optional($investment->investmentPlan)->name ?? '—' }}</td>
                                            <td>{{ optional($investment->investor)->name ?? 'User #' . $investment->user_id }}</td>
                                            <td>{{ number_format($investment->invested_amount, 2) }}</td>
                                            <td>{{ number_format($investment->total_profit, 2) }}</td>
                                            <td>{{ optional($investment->maturity_date)->format('d M Y') ?? '—' }}</td>
                                            <td class="text-right">
                                                <form action="{{ route('admin.payouts.mature', $investment->id) }}" method="post"
                                                    onsubmit="return confirm('Force this investment to maturity and credit all remaining payouts?');">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-warning">Mark matured</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="6" class="text-center text-muted py-3">No active investments.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3">{{ $activeInvestments->links() }}</div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
