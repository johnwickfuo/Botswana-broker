@extends('layouts.base')
@inject('content', 'App\Http\Controllers\FrontController')
@section('title', 'Investor Guide')

@section('content')

<!-- Hero Section -->
<section class="relative bg-white overflow-hidden border-b border-gray-200">
    <div class="container mx-auto px-4 py-24 relative z-10">
        <div class="max-w-4xl mx-auto text-center">
            <div class="inline-block px-3 py-1 text-xs font-semibold tracking-wider uppercase rounded-full bg-sky-50 text-primary mb-6">
                <i class="fas fa-landmark mr-2"></i>Investor Guide
            </div>
            <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6">How to Invest With {{ $settings->site_name }}</h1>
            <p class="text-xl text-gray-600 mb-10 max-w-2xl mx-auto">
                A clear, step-by-step guide to investing in verified national government assets of the Republic of Botswana, with returns paid in Pula.
            </p>

            <script type="application/ld+json">
                {
                    "@context": "http://schema.org",
                    "@type": "BreadcrumbList",
                    "itemListElement": [
                        {
                            "@type": "ListItem",
                            "position": 1,
                            "item": {
                                "@id": "{{ $settings->site_address }}",
                                "name": "{{ $settings->name }}"
                            }
                        },
                        {
                            "@type": "ListItem",
                            "position": 2,
                            "item": {
                                "@id": "{{ $settings->site_address }}investor-guide",
                                "name": "Investor Guide"
                            }
                        }
                    ]
                }
            </script>

            <div class="flex justify-center mt-8">
                <a href="{{ route('invest.index') }}" class="px-6 py-3 bg-primary hover:opacity-90 text-white font-medium rounded-lg flex items-center transition duration-300">
                    <span>Explore Investment Plans</span>
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Step-by-step Section -->
<section id="steps" class="py-16 bg-white">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <div class="inline-block px-3 py-1 text-xs font-semibold tracking-wider uppercase rounded-full bg-sky-50 text-primary mb-4">
                The Process
            </div>
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Investing in Five Steps</h2>
            <p class="text-gray-600 max-w-2xl mx-auto">
                From choosing a plan to receiving your principal at maturity, here is how investing works.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Browse Plans -->
            <div class="relative bg-white p-6 rounded-xl border border-gray-200 transition duration-300 hover:shadow-md h-full">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 rounded-full bg-sky-50 flex items-center justify-center mr-4">
                        <i class="fas fa-building-columns text-primary"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900">1. Browse Plans</h3>
                </div>
                <p class="text-gray-600">
                    Explore the available investment plans. Each plan is tied to a verified national asset of the Republic of Botswana, with published supporting certificates you can review before deciding.
                </p>
            </div>

            <!-- Choose Amount -->
            <div class="relative bg-white p-6 rounded-xl border border-gray-200 transition duration-300 hover:shadow-md h-full">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 rounded-full bg-sky-50 flex items-center justify-center mr-4">
                        <i class="fas fa-coins text-primary"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900">2. Choose Your Amount</h3>
                </div>
                <p class="text-gray-600">
                    Decide how much to invest within the plan's minimum and maximum limits. The applicable limits are shown clearly before you confirm.
                </p>
            </div>

            <!-- Accept Terms -->
            <div class="relative bg-white p-6 rounded-xl border border-gray-200 transition duration-300 hover:shadow-md h-full">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 rounded-full bg-sky-50 flex items-center justify-center mr-4">
                        <i class="fas fa-file-contract text-primary"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900">3. Accept the Terms</h3>
                </div>
                <p class="text-gray-600">
                    Review the plan terms, including the maturity period and how returns are paid. Once you accept, your investment is confirmed.
                </p>
            </div>

            <!-- Funds Locked -->
            <div class="relative bg-white p-6 rounded-xl border border-gray-200 transition duration-300 hover:shadow-md h-full">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 rounded-full bg-sky-50 flex items-center justify-center mr-4">
                        <i class="fas fa-shield-halved text-primary"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900">4. Funds Held to Maturity</h3>
                </div>
                <p class="text-gray-600">
                    Your funds are committed to the plan and remain locked until the agreed maturity date, protected under regulated terms and KYC verification.
                </p>
            </div>
        </div>

        <!-- Returns full-width -->
        <div class="mt-8">
            <div class="relative bg-white p-6 rounded-xl border border-gray-200 transition duration-300 hover:shadow-md">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 rounded-full bg-sky-50 flex items-center justify-center mr-4">
                        <i class="fas fa-handshake text-primary"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900">5. Receive Returns &amp; Principal</h3>
                </div>
                <p class="text-gray-600">
                    Throughout the term you receive periodic returns paid in Pula. When the plan reaches maturity, your original principal is returned to you in full, in accordance with the terms you accepted.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Key Things to Know Section -->
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <div class="inline-block px-3 py-1 text-xs font-semibold tracking-wider uppercase rounded-full bg-sky-50 text-primary mb-4">
                Good to Know
            </div>
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Key Things to Understand</h2>
            <p class="text-gray-600 max-w-2xl mx-auto">
                A few essentials before you invest in national government assets.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white p-6 rounded-xl border border-gray-200 h-full text-center">
                <div class="w-16 h-16 rounded-full bg-sky-50 flex items-center justify-center mb-6 mx-auto">
                    <i class="fas fa-building-columns text-2xl text-primary"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-4">Verified Assets</h3>
                <p class="text-gray-600">
                    Every plan is backed by a documented national asset of the Republic of Botswana.
                </p>
            </div>

            <div class="bg-white p-6 rounded-xl border border-gray-200 h-full text-center">
                <div class="w-16 h-16 rounded-full bg-sky-50 flex items-center justify-center mb-6 mx-auto">
                    <i class="fas fa-coins text-2xl text-primary"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-4">Returns in Pula</h3>
                <p class="text-gray-600">
                    Periodic returns and your principal are paid in Pula under regulated terms.
                </p>
            </div>

            <div class="bg-white p-6 rounded-xl border border-gray-200 h-full text-center">
                <div class="w-16 h-16 rounded-full bg-sky-50 flex items-center justify-center mb-6 mx-auto">
                    <i class="fas fa-shield-halved text-2xl text-primary"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-4">KYC Required</h3>
                <p class="text-gray-600">
                    Identity verification protects your account before you can invest.
                </p>
            </div>

            <div class="bg-white p-6 rounded-xl border border-gray-200 h-full text-center">
                <div class="w-16 h-16 rounded-full bg-sky-50 flex items-center justify-center mb-6 mx-auto">
                    <i class="fas fa-file-contract text-2xl text-primary"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-4">Clear Maturity</h3>
                <p class="text-gray-600">
                    You know in advance how long funds are committed and when principal returns.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action Section -->
<section class="py-16 bg-white">
    <div class="container mx-auto px-4 relative z-10">
        <div class="max-w-4xl mx-auto bg-gray-50 rounded-xl p-8 border border-gray-200">
            <div class="text-center">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Ready to Invest?</h2>
                <p class="text-gray-600 mb-8 max-w-2xl mx-auto">
                    Browse verified national government assets and choose a plan that suits you. Investing takes only a few minutes once your account is verified.
                </p>
                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    <a href="{{ route('invest.index') }}" class="px-6 py-3 bg-primary hover:opacity-90 text-white font-medium rounded-lg transition duration-300 flex items-center justify-center">
                        <span>Explore Investment Plans</span>
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
