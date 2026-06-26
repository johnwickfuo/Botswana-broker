
@extends('layouts.base')
@inject('content', 'App\Http\Controllers\FrontController')
@section('title', 'About Us')

@section('content')

<!-- Hero Section -->
<section class="relative overflow-hidden bg-white border-b border-gray-200">
    <div class="relative z-10 px-4 py-16 mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="text-center">
            <div class="inline-block px-3 py-1 mb-4 text-xs font-semibold tracking-wider uppercase rounded-full bg-sky-50 text-primary">
                <i class="fas fa-landmark mr-2"></i>The Official Investment Platform
            </div>
            <h1 class="text-3xl font-extrabold tracking-tight text-gray-900 sm:text-4xl md:text-5xl">
                <span class="block">About {{ $settings->site_name }}</span>
                <span class="block mt-2 text-primary">Enabling investors to invest in the nation</span>
            </h1>
            <p class="max-w-2xl mt-5 mx-auto text-xl text-gray-600">
                {{ $settings->site_name }} is the official investment platform of the United Arab Emirates, giving investors worldwide a transparent and regulated way to invest in verified national government assets.
            </p>
        </div>
    </div>
</section>

<!-- Mission Section -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900">Our Mission</h2>
            <div class="w-24 h-1 mx-auto mt-4 rounded-full bg-primary"></div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden p-6 md:p-10">
            <div class="prose prose-lg max-w-none text-gray-700">
                <p>
                    {{ $settings->site_name }} exists to make participation in the nation's development accessible to investors worldwide. Through the platform, individuals can place their savings into verified government-backed assets of the United Arab Emirates and earn regulated returns over clearly defined terms.
                </p>
                <p>
                    Every asset offered on the platform is reviewed and documented before it is made available for investment. Supporting certificates are published so that investors can see exactly what stands behind their investment. Returns are paid in your selected currency, and the original principal is returned at maturity in accordance with the terms accepted at the time of investment.
                </p>
                <p>
                    Our purpose is simple: to channel investor savings into the country's long-term priorities while protecting investors through transparency, identity verification, and clear, predictable terms.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Our Values Section -->
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl font-bold text-gray-900">What We Stand For</h2>
            <div class="w-24 h-1 mx-auto mt-4 rounded-full bg-primary"></div>
        </div>

        <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-4">
            <!-- Value Card 1 -->
            <div class="bg-white rounded-xl border border-gray-200 p-6 transition-all duration-300 hover:shadow-md h-full flex flex-col items-center text-center">
                <div class="w-20 h-20 mb-6 rounded-full flex items-center justify-center bg-sky-50">
                    <i class="fas fa-building-columns text-3xl text-primary"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-4">Government-Backed</h3>
                <p class="text-gray-600">Every opportunity is tied to verified national assets of the United Arab Emirates, reviewed before being offered to investors.</p>
            </div>

            <!-- Value Card 2 -->
            <div class="bg-white rounded-xl border border-gray-200 p-6 transition-all duration-300 hover:shadow-md h-full flex flex-col items-center text-center">
                <div class="w-20 h-20 mb-6 rounded-full flex items-center justify-center bg-sky-50">
                    <i class="fas fa-file-contract text-3xl text-primary"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-4">Transparency</h3>
                <p class="text-gray-600">Supporting certificates and clear terms are published for every asset, so investors always know what backs their investment.</p>
            </div>

            <!-- Value Card 3 -->
            <div class="bg-white rounded-xl border border-gray-200 p-6 transition-all duration-300 hover:shadow-md h-full flex flex-col items-center text-center">
                <div class="w-20 h-20 mb-6 rounded-full flex items-center justify-center bg-sky-50">
                    <i class="fas fa-shield-halved text-3xl text-primary"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-4">Protection</h3>
                <p class="text-gray-600">Identity verification (KYC) safeguards every account, and returns are paid in your selected currency under regulated, predictable terms.</p>
            </div>

            <!-- Value Card 4 -->
            <div class="bg-white rounded-xl border border-gray-200 p-6 transition-all duration-300 hover:shadow-md h-full flex flex-col items-center text-center">
                <div class="w-20 h-20 mb-6 rounded-full flex items-center justify-center bg-sky-50">
                    <i class="fas fa-handshake text-3xl text-primary"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-4">Accessibility</h3>
                <p class="text-gray-600">A straightforward process lets investors invest within clear plan limits and follow their holdings with confidence.</p>
            </div>
        </div>
    </div>
</section>

<!-- Governance & Oversight Section -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900">Governance &amp; Oversight</h2>
            <div class="w-24 h-1 mx-auto mt-4 rounded-full bg-primary"></div>
        </div>

        <div class="flex flex-col lg:flex-row gap-8 items-start">
            <div class="w-full lg:w-1/2">
                <div class="bg-gray-50 border border-gray-200 rounded-xl p-6 md:p-8">
                    <p class="text-gray-700">
                        {{ $settings->site_name }} operates under defined governance and oversight intended to protect investors. Assets are documented and verified before listing, investor funds are administered under regulated terms, and the platform maintains records that allow holdings and returns to be tracked over time.
                    </p>
                </div>
            </div>

            <div class="w-full lg:w-1/2">
                <ul class="space-y-4">
                    <li class="flex items-start">
                        <i class="fas fa-shield-halved text-primary mt-1"></i>
                        <p class="ml-3 text-lg font-medium text-gray-900">Verified national assets</p>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-file-contract text-primary mt-1"></i>
                        <p class="ml-3 text-lg font-medium text-gray-900">Published supporting certificates</p>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-coins text-primary mt-1"></i>
                        <p class="ml-3 text-lg font-medium text-gray-900">Returns paid to your wallet</p>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-shield-halved text-primary mt-1"></i>
                        <p class="ml-3 text-lg font-medium text-gray-900">KYC-protected accounts</p>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-handshake text-primary mt-1"></i>
                        <p class="ml-3 text-lg font-medium text-gray-900">Clear maturity terms</p>
                    </li>
                </ul>

                <div class="mt-8">
                    <a href="{{ route('invest.index') }}" class="inline-flex items-center px-6 py-3 text-base font-medium rounded-md shadow-sm text-white bg-primary hover:opacity-90 transition-all duration-200">
                        Explore Investment Plans
                        <svg class="ml-2 -mr-1 w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
