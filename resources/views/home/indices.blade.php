@extends('layouts.base')
@inject('content', 'App\Http\Controllers\FrontController')
@section('title', 'National Development Investments')

@section('content')

<!-- Hero Section -->
<section class="bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <div class="max-w-3xl">
            <div class="inline-flex items-center gap-2 px-4 py-1 rounded-full bg-blue-50 text-blue-600 text-sm font-medium mb-6">
                <i class="fa fa-landmark"></i>
                <span>National Development</span>
            </div>
            <h1 class="text-4xl md:text-5xl font-bold text-gray-900 leading-tight mb-6">
                Invest Across the United Arab Emirates' National Sectors
            </h1>
            <p class="text-lg text-gray-600 leading-relaxed mb-8">
                {{ $settings->site_name }} offers investors worldwide and institutions access to verified United Arab Emirates government assets spanning energy & oil, real estate, ports & logistics, aviation and tourism. Returns are regulated, transparent and paid in your selected currency.
            </p>
            <div class="flex flex-wrap gap-4">
                <a href="{{ route('invest.index') }}" class="inline-flex items-center px-6 py-3 rounded-lg text-base font-medium text-white bg-blue-600 hover:bg-blue-700 transition-colors shadow-sm">
                    View Investment Plans
                    <i class="fa fa-arrow-right ml-2"></i>
                </a>
                <a href="/register" class="inline-flex items-center px-6 py-3 rounded-lg text-base font-medium text-gray-900 bg-white border border-gray-300 hover:bg-gray-50 transition-colors">
                    Register an Account
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Detail Section -->
<section class="py-16 bg-white border-t border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="p-6 rounded-xl border border-gray-200">
                <div class="w-12 h-12 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center mb-4">
                    <i class="fa fa-chart-pie text-xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Broad National Exposure</h3>
                <p class="text-gray-600">Invest across several government sectors to align your portfolio with the nation's development priorities.</p>
            </div>
            <div class="p-6 rounded-xl border border-gray-200">
                <div class="w-12 h-12 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center mb-4">
                    <i class="fa fa-shield-halved text-xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Regulated and Verified</h3>
                <p class="text-gray-600">Every instrument is backed by recognised national assets and administered to transparent public standards.</p>
            </div>
            <div class="p-6 rounded-xl border border-gray-200">
                <div class="w-12 h-12 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center mb-4">
                    <i class="fa fa-file-contract text-xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Clear Returns in Your Currency</h3>
                <p class="text-gray-600">Each plan defines its term and expected return in advance, with all payments settled to your wallet.</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-16 bg-white border-t border-gray-100">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl font-bold text-gray-900 mb-4">Invest across the nation</h2>
        <p class="text-lg text-gray-600 mb-8">Review the available investment plans and choose the sectors that match your goals.</p>
        <a href="{{ route('invest.index') }}" class="inline-flex items-center px-6 py-3 rounded-lg text-base font-medium text-white bg-blue-600 hover:bg-blue-700 transition-colors shadow-sm">
            View Investment Plans
            <i class="fa fa-arrow-right ml-2"></i>
        </a>
    </div>
</section>

@endsection
