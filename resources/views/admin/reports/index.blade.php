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

                <h1 class="title1 text-{{ $text }} mt-2 mb-4">Reports &amp; Exports</h1>

                @include('admin.assets.partials.flash')

                <div class="row">
                    @php($reports = [
                        ['Investments', 'All investments with investor, plan, asset, principal, returns and status.', 'admin.reports.investments', 'fa-chart-line', 'primary'],
                        ['Payouts', 'Every scheduled and processed payout (returns + principal).', 'admin.reports.payouts', 'fa-hand-holding-usd', 'success'],
                        ['Investors', 'Everyone who has invested, with their aggregate position.', 'admin.reports.investors', 'fa-users', 'info'],
                    ])
                    @foreach ($reports as [$title, $desc, $route, $icon, $color])
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body text-center">
                                    <div class="icon-big text-center icon-{{ $color }}">
                                        <i class="fas {{ $icon }} fa-2x"></i>
                                    </div>
                                    <h4 class="mt-3">{{ $title }}</h4>
                                    <p class="text-muted">{{ $desc }}</p>
                                    <a href="{{ route($route) }}" class="btn btn-{{ $color }}">
                                        <i class="fa fa-download"></i> Download CSV
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

            </div>
        </div>
    </div>
@endsection
