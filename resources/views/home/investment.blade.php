@extends('layouts.base')

@section('title', 'Investments')

@inject('content', 'App\Http\Controllers\FrontController')
@section('content')

<!-- Hero Section -->
<section class="relative overflow-hidden bg-white border-b border-gray-200 py-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div>
                <div class="inline-block px-3 py-1 mb-6 text-xs font-semibold tracking-wider uppercase rounded-full bg-sky-50 text-primary">
                    <i class="fas fa-landmark mr-2"></i>Official Investment Platform
                </div>
                <h1 class="text-4xl font-extrabold tracking-tight text-gray-900 sm:text-5xl md:text-6xl mb-4">
                    <span class="block">Invest in the</span>
                    <span class="block text-primary">United Arab Emirates</span>
                </h1>
                <p class="mt-3 text-xl font-medium text-gray-700 mb-6">
                    Verified national assets. Regulated returns in your selected currency.
                </p>
                <p class="text-base text-gray-600 sm:text-lg max-w-xl">
                    {{ $settings->site_name }} lets investors worldwide place their savings into verified government-backed assets of the United Arab Emirates. Each investment follows clear terms: funds are committed until maturity, periodic returns are paid in your selected currency, and your principal is returned when the plan matures.
                </p>
                <div class="mt-8 flex flex-wrap gap-4">
                    <a href="{{ route('invest.index') }}" class="inline-flex items-center px-6 py-3 text-base font-medium rounded-md shadow-sm text-white bg-primary hover:opacity-90 transition duration-300">
                        Browse Investment Plans
                        <svg class="ml-2 -mr-1 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </a>
                    <a href="#how-it-works" class="inline-flex items-center px-6 py-3 border border-gray-300 text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 transition duration-300">
                        How It Works
                    </a>
                </div>
            </div>
            <div class="hidden lg:block">
                <div class="relative bg-white p-8 rounded-2xl border border-gray-200 shadow-sm">
                    <div class="flex items-center mb-6">
                        <div class="w-14 h-14 rounded-full bg-sky-50 flex items-center justify-center mr-4">
                            <i class="fas fa-building-columns text-2xl text-primary"></i>
                        </div>
                        <div>
                            <span class="text-gray-500 text-sm">Investment Overview</span>
                            <h3 class="text-xl font-bold text-gray-900">Government-Backed Assets</h3>
                        </div>
                    </div>
                    <ul class="space-y-4">
                        <li class="flex items-start">
                            <i class="fas fa-shield-halved text-primary mt-1"></i>
                            <span class="ml-3 text-gray-700">Verified and documented national assets</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-file-contract text-primary mt-1"></i>
                            <span class="ml-3 text-gray-700">Published supporting certificates</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-coins text-primary mt-1"></i>
                            <span class="ml-3 text-gray-700">Periodic returns paid to your wallet</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-handshake text-primary mt-1"></i>
                            <span class="ml-3 text-gray-700">Principal returned at maturity</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Investment Plans Section -->
<section id="investment-plans" class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <div class="inline-block px-3 py-1 mb-4 text-xs font-semibold tracking-wider uppercase rounded-full bg-sky-50 text-primary">
                Our Plans
            </div>
            <h2 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">
                <span class="block">Investment Plans</span>
            </h2>
            <p class="mt-4 text-xl text-gray-600 max-w-3xl mx-auto">
                Each plan is tied to verified national assets, with defined amount limits, maturity terms, and returns paid in your selected currency.
            </p>
        </div>

        <!-- Investment Plans Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach ($plans as $plan)
            <div class="bg-white rounded-xl p-6 border border-gray-200 transition-all duration-300 hover:shadow-md hover:-translate-y-1">
                <!-- Header -->
                <div class="flex items-center mb-6">
                    <div class="w-12 h-12 bg-sky-50 rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-building-columns text-primary"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">{{ $plan->name }}</h3>
                        <p class="text-sm text-gray-500">Investment Plan</p>
                    </div>
                </div>

                <!-- Divider -->
                <div class="w-full h-px bg-gray-200 my-4"></div>

                <!-- Plan details -->
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-500">Returns:</span>
                        <span class="text-gray-900 font-medium">Paid to your wallet</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-500">Payment Schedule:</span>
                        <span class="text-gray-900 font-medium">Periodic</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-500">Principal:</span>
                        <span class="text-gray-900 font-medium">Returned at maturity</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-500">Backing:</span>
                        <span class="text-gray-900 font-medium">Verified national asset</span>
                    </div>
                </div>

                <!-- CTA Button -->
                <div class="mt-6">
                    <a href="{{ route('invest.index') }}" class="block w-full py-3 px-4 bg-primary hover:opacity-90 text-white font-medium rounded-lg text-center transition duration-300">
                        View &amp; Invest
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- How It Works Section -->
<section id="how-it-works" class="py-16 bg-white">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <div class="inline-block px-3 py-1 text-xs font-semibold tracking-wider uppercase rounded-full bg-sky-50 text-primary mb-4">
                How Investing Works
            </div>
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">From Plan to Maturity</h2>
            <p class="max-w-3xl mx-auto text-gray-600">
                The process is clear and predictable from start to finish. Follow these steps to invest in verified national government assets.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-6">
            <!-- Step 1 -->
            <div class="bg-white rounded-xl p-6 border border-gray-200 text-center h-full">
                <div class="flex items-center justify-center w-14 h-14 bg-sky-50 rounded-full mb-5 mx-auto">
                    <span class="text-xl font-bold text-primary">1</span>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">Browse Plans</h3>
                <p class="text-gray-600 text-sm">Explore the available plans and review the verified national asset behind each one.</p>
            </div>

            <!-- Step 2 -->
            <div class="bg-white rounded-xl p-6 border border-gray-200 text-center h-full">
                <div class="flex items-center justify-center w-14 h-14 bg-sky-50 rounded-full mb-5 mx-auto">
                    <span class="text-xl font-bold text-primary">2</span>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">Choose Amount</h3>
                <p class="text-gray-600 text-sm">Select an amount within the plan's minimum and maximum limits.</p>
            </div>

            <!-- Step 3 -->
            <div class="bg-white rounded-xl p-6 border border-gray-200 text-center h-full">
                <div class="flex items-center justify-center w-14 h-14 bg-sky-50 rounded-full mb-5 mx-auto">
                    <span class="text-xl font-bold text-primary">3</span>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">Accept Terms</h3>
                <p class="text-gray-600 text-sm">Review and accept the plan terms, including the maturity period.</p>
            </div>

            <!-- Step 4 -->
            <div class="bg-white rounded-xl p-6 border border-gray-200 text-center h-full">
                <div class="flex items-center justify-center w-14 h-14 bg-sky-50 rounded-full mb-5 mx-auto">
                    <span class="text-xl font-bold text-primary">4</span>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">Funds Locked</h3>
                <p class="text-gray-600 text-sm">Your funds are committed to the plan and held until maturity.</p>
            </div>

            <!-- Step 5 -->
            <div class="bg-white rounded-xl p-6 border border-gray-200 text-center h-full">
                <div class="flex items-center justify-center w-14 h-14 bg-sky-50 rounded-full mb-5 mx-auto">
                    <span class="text-xl font-bold text-primary">5</span>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">Returns &amp; Principal</h3>
                <p class="text-gray-600 text-sm">Receive periodic returns in your selected currency, plus your principal back at maturity.</p>
            </div>
        </div>

        <div class="mt-12 text-center">
            <a href="{{ route('invest.index') }}" class="inline-flex items-center px-6 py-3 bg-primary text-white font-medium rounded-lg transition duration-300 hover:opacity-90">
                <span>Start Investing</span>
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                </svg>
            </a>
        </div>
    </div>
</section>

<!-- Assurance Section -->
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white rounded-xl p-6 border border-gray-200 text-center h-full">
                <div class="w-16 h-16 rounded-full bg-sky-50 flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-shield-halved text-2xl text-primary"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Verified Assets</h3>
                <p class="text-gray-600">Every plan is backed by a documented national asset of the United Arab Emirates, reviewed before listing.</p>
            </div>

            <div class="bg-white rounded-xl p-6 border border-gray-200 text-center h-full">
                <div class="w-16 h-16 rounded-full bg-sky-50 flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-file-contract text-2xl text-primary"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Published Certificates</h3>
                <p class="text-gray-600">Supporting certificates are published so you can see exactly what stands behind your investment.</p>
            </div>

            <div class="bg-white rounded-xl p-6 border border-gray-200 text-center h-full">
                <div class="w-16 h-16 rounded-full bg-sky-50 flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-coins text-2xl text-primary"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Returns to Your Wallet</h3>
                <p class="text-gray-600">Returns are paid in your selected currency under regulated terms, with principal returned at maturity.</p>
            </div>
        </div>
    </div>
</section>

@endsection
