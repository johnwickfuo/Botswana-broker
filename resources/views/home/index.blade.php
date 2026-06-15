@extends('layouts.base')

@section('title', 'Republic of Botswana Investment Platform')

@section('content')

<!-- Hero -->
<section class="relative overflow-hidden bg-gray-900 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 lg:py-32">
        <div class="max-w-3xl">
            <p class="text-sm font-semibold uppercase tracking-widest" style="color:#00A3DD">Republic of Botswana</p>
            <h1 class="mt-4 text-4xl sm:text-5xl font-extrabold leading-tight">
                Invest in the nation's verified government assets
            </h1>
            <p class="mt-6 text-lg text-gray-300">
                {{ $settings->site_name }} is the official platform for citizens to invest in verified national assets of the Republic of Botswana — with transparent, regulated returns paid in Pula.
            </p>
            <div class="mt-10 flex flex-wrap gap-4">
                <a href="{{ route('invest.index') }}" class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-lg transition-colors">
                    View Investment Plans
                </a>
                <a href="register" class="inline-flex items-center border border-white/30 hover:border-white text-white font-semibold px-6 py-3 rounded-lg transition-colors">
                    Create an Account
                </a>
            </div>
        </div>
    </div>
    <!-- flag-nod divider -->
    <div class="h-1 bg-black border-t-2 border-white"></div>
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
                ['fa-user-check', 'Register &amp; verify', 'Create an account and complete KYC with your Omang / national ID.'],
                ['fa-landmark', 'Choose an asset plan', 'Browse verified government assets and select a plan and amount within its limits.'],
                ['fa-file-contract', 'Accept terms &amp; invest', 'Confirm the terms; your funds are committed and locked until maturity.'],
                ['fa-coins', 'Earn returns', 'Receive scheduled returns in Pula, with your principal repaid at maturity.'],
            ])
            @foreach ($steps as $i => [$icon, $title, $desc])
                <div class="text-center">
                    <div class="mx-auto w-14 h-14 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 text-xl">
                        <i class="fas {{ $icon }}"></i>
                    </div>
                    <h3 class="mt-5 font-semibold text-gray-900">{{ $i + 1 }}. {!! $title !!}</h3>
                    <p class="mt-2 text-sm text-gray-600">{!! $desc !!}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Asset categories -->
<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto">
            <h2 class="text-3xl font-bold text-gray-900">National asset categories</h2>
            <p class="mt-3 text-gray-600">Investments are backed by tangible assets of the Republic.</p>
        </div>
        <div class="mt-14 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @php($cats = [
                ['fa-mountain', 'Mining', 'Strategic mineral and mining assets.'],
                ['fa-bolt', 'Energy', 'Power generation and energy infrastructure.'],
                ['fa-road', 'Infrastructure', 'Roads, transport and public works.'],
                ['fa-faucet-drip', 'Water', 'Water supply and sanitation assets.'],
                ['fa-wheat-awn', 'Agriculture', 'Agricultural and food-security projects.'],
                ['fa-building-columns', 'Public institutions', 'State enterprises and public holdings.'],
            ])
            @foreach ($cats as [$icon, $title, $desc])
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 flex items-start gap-4">
                    <div class="w-11 h-11 rounded-lg bg-gray-900 text-white flex items-center justify-center flex-shrink-0">
                        <i class="fas {{ $icon }}"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900">{{ $title }}</h3>
                        <p class="mt-1 text-sm text-gray-600">{{ $desc }}</p>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-12 text-center">
            <a href="{{ route('invest.index') }}" class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-lg transition-colors">
                Browse all investment plans
            </a>
        </div>
    </div>
</section>

<!-- Why invest -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto">
            <h2 class="text-3xl font-bold text-gray-900">Why invest with us</h2>
        </div>
        <div class="mt-14 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            @php($whys = [
                ['fa-shield-halved', 'Government-backed', 'Every plan is linked to a verified national asset.'],
                ['fa-file-lines', 'Transparent', 'Asset certificates are published for citizens to view.'],
                ['fa-coins', 'Returns in Pula', 'Returns and principal are paid to your wallet in Pula.'],
                ['fa-lock', 'Protected', 'KYC-verified access and funds secured until maturity.'],
            ])
            @foreach ($whys as [$icon, $title, $desc])
                <div>
                    <div class="w-12 h-12 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-lg">
                        <i class="fas {{ $icon }}"></i>
                    </div>
                    <h3 class="mt-4 font-semibold text-gray-900">{{ $title }}</h3>
                    <p class="mt-2 text-sm text-gray-600">{{ $desc }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- CTA band -->
<section class="bg-gray-900 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 text-center">
        <h2 class="text-3xl font-bold">Start investing in the Republic of Botswana</h2>
        <p class="mt-4 text-gray-300 max-w-2xl mx-auto">Create your account, complete verification, and choose a plan backed by the nation's assets.</p>
        <div class="mt-8 flex flex-wrap justify-center gap-4">
            <a href="register" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-lg transition-colors">Create an Account</a>
            <a href="{{ route('invest.index') }}" class="border border-white/30 hover:border-white text-white font-semibold px-6 py-3 rounded-lg transition-colors">View Plans</a>
        </div>
    </div>
</section>

@endsection
