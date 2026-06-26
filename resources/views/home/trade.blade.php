@extends('layouts.base')
@inject('content', 'App\Http\Controllers\FrontController')
@section('title', 'Invest with the United Arab Emirates')

@section('content')

<!-- Hero Section -->
<section class="bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <div class="max-w-3xl">
            <div class="inline-flex items-center gap-2 px-4 py-1 rounded-full bg-blue-50 text-blue-600 text-sm font-medium mb-6">
                <i class="fa fa-landmark"></i>
                <span>Official Investment Platform</span>
            </div>
            <h1 class="text-4xl md:text-5xl font-bold text-gray-900 leading-tight mb-6">
                Invest in the United Arab Emirates
            </h1>
            <p class="text-lg text-gray-600 leading-relaxed mb-8">
                {{ $settings->site_name }} is the official platform for investors and institutions worldwide to invest in verified national government assets. Place your capital in regulated, transparent instruments and earn returns paid to your wallet.
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

<!-- Principles Section -->
<section class="py-16 bg-white border-t border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="p-6 rounded-xl border border-gray-200">
                <div class="w-12 h-12 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center mb-4">
                    <i class="fa fa-shield-halved text-xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Regulated and Secure</h3>
                <p class="text-gray-600">Every instrument offered is backed by verified national assets and administered under transparent public oversight.</p>
            </div>
            <div class="p-6 rounded-xl border border-gray-200">
                <div class="w-12 h-12 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center mb-4">
                    <i class="fa fa-file-contract text-xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Transparent Returns</h3>
                <p class="text-gray-600">Each plan states its term and expected return clearly before you commit, with all payments settled in your selected currency.</p>
            </div>
            <div class="p-6 rounded-xl border border-gray-200">
                <div class="w-12 h-12 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center mb-4">
                    <i class="fa fa-chart-pie text-xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">National Portfolio</h3>
                <p class="text-gray-600">Support development across energy, oil, real estate, infrastructure, ports and logistics, aviation and tourism while building your own portfolio.</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-16 bg-white border-t border-gray-100">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl font-bold text-gray-900 mb-4">Begin investing today</h2>
        <p class="text-lg text-gray-600 mb-8">Review the available investment plans and choose the instruments that match your goals.</p>
        <a href="{{ route('invest.index') }}" class="inline-flex items-center px-6 py-3 rounded-lg text-base font-medium text-white bg-blue-600 hover:bg-blue-700 transition-colors shadow-sm">
            View Investment Plans
            <i class="fa fa-arrow-right ml-2"></i>
        </a>
    </div>
</section>

@endsection
