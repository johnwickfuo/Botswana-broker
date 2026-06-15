
@extends('layouts.base')
@inject('content', 'App\Http\Controllers\FrontController')
@section('title', 'Why Invest With Us')

@section('content')

<!-- Hero Section -->
<section class="relative bg-white overflow-hidden border-b border-gray-200">
    <div class="container mx-auto px-4 pt-24 pb-16 relative z-10">
        <div class="max-w-4xl">
            <div class="space-y-6">
                <div class="inline-block px-4 py-1 rounded-full bg-sky-50 border border-sky-100">
                    <p class="text-sm font-medium text-primary"><i class="fas fa-landmark mr-2"></i>Invest in the Nation</p>
                </div>
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-gray-900 leading-tight">
                    Why Invest With <span class="text-primary">{{ $settings->site_name }}</span>
                </h1>
                <p class="text-lg text-gray-600 max-w-2xl">
                    {{ $settings->site_name }} gives citizens a transparent, regulated route to invest in verified national government assets of the Republic of Botswana, with returns paid in Pula.
                </p>

                <!-- Breadcrumb -->
                <nav class="flex" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <a href="{{ $settings->site_address }}" class="text-gray-500 hover:text-primary transition-colors">
                                Home
                            </a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <span class="text-gray-400 mx-2">/</span>
                                <span class="text-gray-700">Why Invest With Us</span>
                            </div>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>

<!-- Reasons Grid Section -->
<section class="py-16 bg-white">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Reasons to Invest</h2>
            <div class="w-24 h-1 mx-auto rounded-full bg-primary"></div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <!-- Government-backed -->
            <div class="h-full p-6 bg-white rounded-xl border border-gray-200 transition-all duration-300 hover:shadow-md text-center">
                <div class="w-16 h-16 mx-auto mb-6 bg-sky-50 rounded-xl flex items-center justify-center">
                    <i class="fas fa-building-columns text-2xl text-primary"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-4">Government-Backed Assets</h3>
                <p class="text-gray-600">Every plan is tied to verified national assets of the Republic of Botswana, reviewed before being offered to citizens.</p>
            </div>

            <!-- Transparency -->
            <div class="h-full p-6 bg-white rounded-xl border border-gray-200 transition-all duration-300 hover:shadow-md text-center">
                <div class="w-16 h-16 mx-auto mb-6 bg-sky-50 rounded-xl flex items-center justify-center">
                    <i class="fas fa-file-contract text-2xl text-primary"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-4">Full Transparency</h3>
                <p class="text-gray-600">Supporting certificates are published for each asset, so you can see exactly what backs your investment.</p>
            </div>

            <!-- Returns in Pula -->
            <div class="h-full p-6 bg-white rounded-xl border border-gray-200 transition-all duration-300 hover:shadow-md text-center">
                <div class="w-16 h-16 mx-auto mb-6 bg-sky-50 rounded-xl flex items-center justify-center">
                    <i class="fas fa-coins text-2xl text-primary"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-4">Returns Paid in Pula</h3>
                <p class="text-gray-600">Periodic returns are paid in Pula, and your original principal is returned at maturity under the agreed terms.</p>
            </div>

            <!-- KYC protected -->
            <div class="h-full p-6 bg-white rounded-xl border border-gray-200 transition-all duration-300 hover:shadow-md text-center">
                <div class="w-16 h-16 mx-auto mb-6 bg-sky-50 rounded-xl flex items-center justify-center">
                    <i class="fas fa-shield-halved text-2xl text-primary"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-4">KYC-Protected</h3>
                <p class="text-gray-600">Identity verification safeguards every account, helping ensure investments are held securely by the rightful citizen.</p>
            </div>
        </div>
    </div>
</section>

<!-- Trust Indicators Section -->
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                <span class="text-primary">Built on Trust</span>
            </h2>
            <p class="text-lg text-gray-600">A regulated platform designed to protect citizen investors at every step.</p>
        </div>
    </div>
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <!-- Regulated returns -->
            <div class="h-full p-6 bg-white rounded-xl border border-gray-200 transition-all duration-300 hover:shadow-md text-center">
                <div class="w-16 h-16 mx-auto mb-6 bg-sky-50 rounded-xl flex items-center justify-center">
                    <i class="fas fa-landmark text-2xl text-primary"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-4">Regulated Returns</h3>
                <p class="text-gray-600">Returns follow clear, predetermined terms rather than speculative outcomes.</p>
            </div>

            <!-- Verified assets -->
            <div class="h-full p-6 bg-white rounded-xl border border-gray-200 transition-all duration-300 hover:shadow-md text-center">
                <div class="w-16 h-16 mx-auto mb-6 bg-sky-50 rounded-xl flex items-center justify-center">
                    <i class="fas fa-file-contract text-2xl text-primary"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-4">Verified &amp; Documented</h3>
                <p class="text-gray-600">Each asset is documented with published certificates before it is offered.</p>
            </div>

            <!-- Clear maturity -->
            <div class="h-full p-6 bg-white rounded-xl border border-gray-200 transition-all duration-300 hover:shadow-md text-center">
                <div class="w-16 h-16 mx-auto mb-6 bg-sky-50 rounded-xl flex items-center justify-center">
                    <i class="fas fa-handshake text-2xl text-primary"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-4">Clear Maturity Terms</h3>
                <p class="text-gray-600">You know in advance how long funds are committed and when principal is returned.</p>
            </div>

            <!-- Secure -->
            <div class="h-full p-6 bg-white rounded-xl border border-gray-200 transition-all duration-300 hover:shadow-md text-center">
                <div class="w-16 h-16 mx-auto mb-6 bg-sky-50 rounded-xl flex items-center justify-center">
                    <i class="fas fa-shield-halved text-2xl text-primary"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-4">Secure Accounts</h3>
                <p class="text-gray-600">KYC verification and protected records keep each citizen's holdings safe.</p>
            </div>
        </div>

        <div class="mt-12 text-center">
            <a href="{{ route('invest.index') }}" class="inline-flex items-center px-6 py-3 text-base font-medium rounded-md shadow-sm text-white bg-primary hover:opacity-90 transition duration-200">
                View Investment Plans
                <svg class="ml-2 -mr-1 w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
            </a>
        </div>
    </div>
</section>

@endsection
