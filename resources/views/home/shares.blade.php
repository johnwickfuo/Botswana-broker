@extends('layouts.base')
@inject('content', 'App\Http\Controllers\FrontController')
@section('title', 'National Asset Investments')

@section('content')

<!-- Hero Section -->
<section class="bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <div class="max-w-3xl">
            <div class="inline-flex items-center gap-2 px-4 py-1 rounded-full bg-blue-50 text-blue-600 text-sm font-medium mb-6">
                <i class="fa fa-landmark"></i>
                <span>National Asset Investments</span>
            </div>
            <h1 class="text-4xl md:text-5xl font-bold text-gray-900 leading-tight mb-6">
                Hold a Stake in the United Arab Emirates' National Assets
            </h1>
            <p class="text-lg text-gray-600 leading-relaxed mb-8">
                {{ $settings->site_name }} allows investors worldwide and institutions to invest in verified United Arab Emirates government assets across the nation's key sectors. Earn regulated, transparent returns paid to your wallet while supporting national growth.
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
                    <i class="fa fa-coins text-xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Verified Holdings</h3>
                <p class="text-gray-600">Each investment is tied to recognised government assets in energy & oil, real estate, ports & logistics, aviation and tourism.</p>
            </div>
            <div class="p-6 rounded-xl border border-gray-200">
                <div class="w-12 h-12 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center mb-4">
                    <i class="fa fa-shield-halved text-xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Regulated Oversight</h3>
                <p class="text-gray-600">All plans are administered to transparent national standards under public oversight.</p>
            </div>
            <div class="p-6 rounded-xl border border-gray-200">
                <div class="w-12 h-12 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center mb-4">
                    <i class="fa fa-file-contract text-xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Returns in Your Currency</h3>
                <p class="text-gray-600">Terms and expected returns are stated clearly before you commit, with payments settled to your wallet.</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-16 bg-white border-t border-gray-100">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl font-bold text-gray-900 mb-4">Take your stake in the nation</h2>
        <p class="text-lg text-gray-600 mb-8">Explore the available investment plans and choose the assets that suit your goals.</p>
        <a href="{{ route('invest.index') }}" class="inline-flex items-center px-6 py-3 rounded-lg text-base font-medium text-white bg-blue-600 hover:bg-blue-700 transition-colors shadow-sm">
            View Investment Plans
            <i class="fa fa-arrow-right ml-2"></i>
        </a>
    </div>
</section>

@endsection
