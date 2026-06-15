@extends('layouts.base')

@section('title', 'Republic of Botswana Investment Platform')

@section('content')

<!-- Hero -->
<section class="relative text-white">
    <div class="absolute inset-0 bg-center bg-cover" style="background-image: linear-gradient(rgba(17,17,17,.78), rgba(17,17,17,.82)), url('{{ asset('images/botswana/okavango.jpg') }}');"></div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-28 lg:py-36">
        <div class="max-w-3xl">
            <div class="inline-flex items-center gap-3 mb-6">
                <img src="{{ asset('images/botswana/flag.svg') }}" alt="Flag of the Republic of Botswana" class="h-7 w-auto rounded-sm shadow">
                <span class="text-sm font-semibold uppercase tracking-widest text-flag-blue" style="color:#75AADB">Republic of Botswana</span>
            </div>
            <h1 class="bw-display text-4xl sm:text-5xl lg:text-6xl">
                Invest in the assets that build the nation
            </h1>
            <p class="mt-6 text-lg text-gray-200 max-w-2xl">
                {{ $settings->site_name }} is the official platform for citizens to invest in verified national government assets — transparent terms, regulated returns, all in Pula.
            </p>
            <div class="mt-10 flex flex-wrap gap-4">
                <a href="{{ route('invest.index') }}" class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white font-semibold px-7 py-3.5 rounded-lg transition-colors">
                    View Investment Plans
                </a>
                <a href="register" class="inline-flex items-center bg-white/10 hover:bg-white/20 border border-white/40 text-white font-semibold px-7 py-3.5 rounded-lg transition-colors">
                    Create an Account
                </a>
            </div>
        </div>
    </div>
    <div class="bw-flag-stripe"></div>
</section>

<!-- Trust band -->
<section class="bg-white py-12 border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
        @php($trust = [
            ['fa-shield-halved', 'Government-backed', 'Verified national assets'],
            ['fa-file-lines', 'Transparent', 'Published certificates'],
            ['fa-coins', 'Paid in Pula', 'Returns to your wallet'],
            ['fa-lock', 'Protected', 'KYC-verified & secured'],
        ])
        @foreach ($trust as [$icon, $title, $sub])
            <div>
                <div class="mx-auto w-12 h-12 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 text-lg"><i class="fas {{ $icon }}"></i></div>
                <p class="mt-4 font-semibold text-gray-900">{{ $title }}</p>
                <p class="text-sm text-gray-500">{{ $sub }}</p>
            </div>
        @endforeach
    </div>
</section>

<!-- National asset categories (with imagery) -->
<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-2xl">
            <h2 class="text-3xl font-bold text-gray-900">National asset categories</h2>
            <p class="mt-3 text-gray-600">Investments are backed by tangible assets of the Republic of Botswana.</p>
        </div>
        <div class="mt-12 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @php($cats = [
                ['Mining &amp; Minerals', 'Diamonds, copper and strategic minerals.', 'mining.jpg'],
                ['Energy', 'Power generation and national energy supply.', 'landscape.jpg'],
                ['Infrastructure', 'Roads, transport and public works.', 'gaborone.jpg'],
                ['Water &amp; Sanitation', 'Water security for communities.', 'okavango.jpg'],
                ['Agriculture', 'Farming, livestock and food security.', 'agriculture.jpg'],
                ['Tourism &amp; Wildlife', 'Conservation and the visitor economy.', 'wildlife.jpg'],
            ])
            @foreach ($cats as [$title, $desc, $img])
                <div class="group bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-lg border border-gray-100 transition-shadow">
                    <div class="h-44 bg-center bg-cover" style="background-image:url('{{ asset('images/botswana/'.$img) }}');"></div>
                    <div class="bw-flag-stripe" style="height:6px;border-width:2px"></div>
                    <div class="p-6">
                        <h3 class="font-semibold text-gray-900">{!! $title !!}</h3>
                        <p class="mt-1 text-sm text-gray-600">{{ $desc }}</p>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-12">
            <a href="{{ route('invest.index') }}" class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white font-semibold px-7 py-3.5 rounded-lg transition-colors">Browse all investment plans</a>
        </div>
    </div>
</section>

<!-- How it works -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto">
            <h2 class="text-3xl font-bold text-gray-900">How it works</h2>
            <p class="mt-3 text-gray-600">A clear, regulated path from registration to returns.</p>
        </div>
        <div class="mt-14 grid grid-cols-1 md:grid-cols-4 gap-8">
            @php($steps = [
                ['fa-user-check', 'Register &amp; verify', 'Create an account and complete KYC with your Omang.'],
                ['fa-landmark', 'Choose an asset plan', 'Select a plan and an amount within its limits.'],
                ['fa-file-contract', 'Accept terms &amp; invest', 'Funds are committed and locked until maturity.'],
                ['fa-coins', 'Earn returns', 'Scheduled returns in Pula; principal back at maturity.'],
            ])
            @foreach ($steps as $i => [$icon, $title, $desc])
                <div class="text-center">
                    <div class="mx-auto w-14 h-14 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 text-xl"><i class="fas {{ $icon }}"></i></div>
                    <h3 class="mt-5 font-semibold text-gray-900">{{ $i + 1 }}. {!! $title !!}</h3>
                    <p class="mt-2 text-sm text-gray-600">{!! $desc !!}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Backed by the nation (feature with image) -->
<section class="py-20 bg-gray-900 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid lg:grid-cols-2 gap-12 items-center">
        <div>
            <span class="text-sm font-semibold uppercase tracking-widest" style="color:#75AADB">National investment, citizen ownership</span>
            <h2 class="mt-4 text-3xl font-bold">A stake in Botswana's future</h2>
            <p class="mt-4 text-gray-300">
                Every plan is tied to a verified government asset, with its certificate published for citizens to view. Returns are calculated transparently and paid in Pula, and your principal is protected until maturity.
            </p>
            <ul class="mt-6 space-y-3 text-gray-200">
                <li class="flex items-start gap-3"><i class="fas fa-circle-check mt-1" style="color:#75AADB"></i><span>Asset-backed, government-verified investment plans</span></li>
                <li class="flex items-start gap-3"><i class="fas fa-circle-check mt-1" style="color:#75AADB"></i><span>Publicly viewable certificates &amp; clear terms</span></li>
                <li class="flex items-start gap-3"><i class="fas fa-circle-check mt-1" style="color:#75AADB"></i><span>Returns and principal paid in Pula to your wallet</span></li>
            </ul>
            <a href="register" class="mt-8 inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white font-semibold px-7 py-3.5 rounded-lg transition-colors">Get started</a>
        </div>
        <div class="relative">
            <img src="{{ asset('images/botswana/gaborone.jpg') }}" alt="Botswana" class="rounded-2xl shadow-2xl w-full object-cover h-80">
            <div class="absolute -bottom-3 left-6 right-6 bw-flag-stripe rounded"></div>
        </div>
    </div>
</section>

<!-- CTA band -->
<section class="bw-flag-band">
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 text-center">
        <div class="relative inline-block bg-white/95 rounded-2xl px-8 py-10 shadow-xl">
            <h2 class="text-3xl font-bold text-gray-900">Start investing in the Republic of Botswana</h2>
            <p class="mt-3 text-gray-600 max-w-xl mx-auto">Create your account, complete verification and choose a plan backed by the nation's assets.</p>
            <div class="mt-7 flex flex-wrap justify-center gap-4">
                <a href="register" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-7 py-3.5 rounded-lg transition-colors">Create an Account</a>
                <a href="{{ route('invest.index') }}" class="border border-gray-300 hover:border-gray-900 text-gray-900 font-semibold px-7 py-3.5 rounded-lg transition-colors">View Plans</a>
            </div>
        </div>
    </div>
</section>

@endsection
