<?php
$text = Auth('admin')->User()->dashboard_style == 'light' ? 'dark' : 'light';
$cur = optional($settings)->currency ?? 'P';
?>
@extends('layouts.app')
@section('content')
    @include('admin.topmenu')
    @include('admin.sidebar')
    <div class="main-panel">
        <div class="content">
            <div class="page-inner">

                <h1 class="title1 text-{{ $text }} mt-2 mb-4">Investment Dashboard</h1>

                {{-- Headline cards --}}
                <div class="row">
                    @php($cards = [
                        ['Total raised', $cur . number_format($totalRaised, 2), 'primary', 'fa-coins'],
                        ['Active investments', number_format($activeCount), 'info', 'fa-chart-line'],
                        ['Active value', $cur . number_format($activeValue, 2), 'success', 'fa-wallet'],
                        ['Returns paid', $cur . number_format($returnsPaid, 2), 'secondary', 'fa-hand-holding-usd'],
                        ['Matured', number_format($maturedCount), 'dark', 'fa-check-circle'],
                        ['Payouts due', $payoutsDueCount . ' (' . $cur . number_format($payoutsDueTotal, 2) . ')', 'warning', 'fa-clock'],
                    ])
                    @foreach ($cards as [$label, $value, $color, $icon])
                        <div class="col-md-4 col-sm-6">
                            <div class="card card-stats card-round">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-icon">
                                            <div class="icon-big text-center icon-{{ $color }} bubble-shadow-small">
                                                <i class="fas {{ $icon }}"></i>
                                            </div>
                                        </div>
                                        <div class="col col-stats ml-3 ml-sm-0">
                                            <div class="numbers">
                                                <p class="card-category">{{ $label }}</p>
                                                <h4 class="card-title">{{ $value }}</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="row">
                    {{-- Raised per asset --}}
                    <div class="col-md-5">
                        <div class="card">
                            <div class="card-header"><h4 class="card-title mb-0">Total Raised per Asset</h4></div>
                            <div class="card-body">
                                <table class="table">
                                    <thead><tr><th>Asset</th><th class="text-right">Raised</th></tr></thead>
                                    <tbody>
                                        @forelse ($perAsset as $assetName => $raised)
                                            <tr><td>{{ $assetName }}</td><td class="text-right">{{ $cur }}{{ number_format($raised, 2) }}</td></tr>
                                        @empty
                                            <tr><td colspan="2" class="text-center text-muted py-3">No investments yet.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    {{-- Raised per plan --}}
                    <div class="col-md-7">
                        <div class="card">
                            <div class="card-header"><h4 class="card-title mb-0">Total Raised per Plan</h4></div>
                            <div class="card-body">
                                <table class="table">
                                    <thead><tr><th>Plan</th><th>Asset</th><th class="text-right">Investors</th><th class="text-right">Raised</th></tr></thead>
                                    <tbody>
                                        @forelse ($perPlan as $row)
                                            @php($plan = $plans->get($row->plan_id))
                                            <tr>
                                                <td>{{ optional($plan)->name ?? 'Plan #' . $row->plan_id }}</td>
                                                <td>{{ optional(optional($plan)->asset)->name ?? '—' }}</td>
                                                <td class="text-right">{{ number_format($row->investors) }}</td>
                                                <td class="text-right">{{ $cur }}{{ number_format($row->raised, 2) }}</td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="4" class="text-center text-muted py-3">No investments yet.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <a href="{{ route('admin.payouts.index') }}" class="btn btn-outline-primary mr-2">Payout management</a>
                        <a href="{{ route('admin.reports.index') }}" class="btn btn-outline-secondary mr-2">Reports &amp; exports</a>
                        <a href="{{ url('admin/dashboard/mdeposits') }}" class="btn btn-outline-info mr-2">Deposits</a>
                        <a href="{{ url('admin/dashboard/mwithdrawals') }}" class="btn btn-outline-info mr-2">Withdrawals</a>
                        <a href="{{ route('kyc') }}" class="btn btn-outline-dark">KYC queue</a>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
