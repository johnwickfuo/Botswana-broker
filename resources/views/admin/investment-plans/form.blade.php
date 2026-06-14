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

                <p>
                    <a href="{{ route('admin.investment-plans.index') }}">
                        <i class="p-2 rounded-lg fa fa-arrow-circle-left fa-2x bg-light"></i>
                    </a>
                </p>

                <div class="mt-2 mb-4">
                    <h1 class="title1 text-{{ $text }}">{{ $planId ? 'Edit' : 'New' }} Investment Plan</h1>
                </div>

                @livewire('admin.plan-builder', ['planId' => $planId])

            </div>
        </div>
    </div>
@endsection
