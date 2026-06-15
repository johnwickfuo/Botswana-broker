@extends('layouts.dasht')
@section('title', $title)
@section('content')
<div class="container mx-auto px-3 sm:px-4 lg:px-6 py-4 sm:py-6 lg:py-8" x-data="{ showCopied: false }">

    <x-danger-alert />
    <x-success-alert />
    <x-notify-alert />

    <!-- ============================================================= -->
    <!-- Hero / Welcome band                                           -->
    <!-- ============================================================= -->
    <div class="relative overflow-hidden rounded-2xl mb-6 sm:mb-8 ring-1 ring-black/10 shadow-sm">
        <!-- Botswana imagery + dark overlay -->
        <div class="absolute inset-0 bg-center bg-cover"
             style="background-image: url('{{ asset('images/botswana/okavango.jpg') }}');"></div>
        <div class="absolute inset-0 bg-gradient-to-r from-[#0b1f2a]/95 via-[#0b1f2a]/80 to-[#00A3DD]/40"></div>

        <!-- Flag-stripe accent: thin black bar bordered top & bottom by white -->
        <div class="bw-flag-stripe absolute top-0 inset-x-0" style="height:8px;background:#111111;border-top:2px solid #fff;border-bottom:2px solid #fff;"></div>

        <div class="relative px-5 sm:px-8 lg:px-10 pt-8 pb-7 sm:pt-10 sm:pb-9">
            <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-6">
                <div class="text-center lg:text-left">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 backdrop-blur-sm text-white/90 text-[11px] sm:text-xs font-medium ring-1 ring-white/20 mb-3">
                        <i data-lucide="landmark" class="w-3.5 h-3.5"></i>
                        Republic of Botswana Investment Platform
                    </div>
                    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-white tracking-tight">
                        Dumela, {{ Auth::user()->name }}
                    </h1>
                    <p class="text-sm sm:text-base text-white/70 mt-1">Your national investment dashboard overview</p>

                    <!-- Prominent wallet balance in Pula -->
                    <div class="mt-5">
                        <p class="text-[11px] sm:text-xs uppercase tracking-wider text-white/60">Available wallet balance</p>
                        <div class="flex items-baseline justify-center lg:justify-start gap-2 mt-1">
                            <span class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-white break-all">
                                {{ Auth::user()->currency }}{{ number_format(Auth::user()->account_bal ?? 0, 2, '.', ',') }}
                            </span>
                            <span class="text-xs sm:text-sm font-medium text-white/60">Pula</span>
                        </div>
                    </div>
                </div>

                <!-- Hero actions -->
                <div class="flex flex-col sm:flex-row gap-2 sm:gap-3 w-full lg:w-auto">
                    @if($settings->wallet_status == "on")
                        <a href="#" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-white/10 backdrop-blur-sm ring-1 ring-white/25 text-white rounded-xl hover:bg-white/20 transition text-sm font-medium">
                            <i data-lucide="link" class="w-4 h-4"></i> Connect Wallet
                        </a>
                    @else
                        <div class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-emerald-500/20 ring-1 ring-emerald-300/30 text-emerald-50 rounded-xl text-sm font-medium">
                            <i data-lucide="check-circle" class="w-4 h-4"></i> Wallet Connected
                        </div>
                    @endif
                    <a href="{{ route('invest.index') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-white font-semibold shadow-lg shadow-black/20 hover:opacity-90 transition text-sm" style="background:#00A3DD;">
                        <i data-lucide="landmark" class="w-4 h-4"></i> Browse Plans
                    </a>
                    <a href="{{ route('investments.mine') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-white text-[#0b1f2a] font-semibold shadow-lg shadow-black/20 hover:bg-gray-100 transition text-sm">
                        <i data-lucide="file-text" class="w-4 h-4"></i> My Portfolio
                    </a>
                </div>
            </div>
        </div>
    </div>


    <!-- ============================================================= -->
    <!-- Invest in National Assets                                     -->
    <!-- ============================================================= -->
    <div class="mb-6 sm:mb-8">
        <div class="flex items-center justify-between mb-3">
            <div class="flex items-center gap-2">
                <span class="w-8 h-8 rounded-lg flex items-center justify-center bg-[#75AADB]/15 text-[#00A3DD]">
                    <i data-lucide="landmark" class="w-4 h-4"></i>
                </span>
                <h2 class="text-base sm:text-lg font-semibold text-gray-800 dark:text-gray-100">Invest in National Assets</h2>
            </div>
            <a href="{{ route('invest.index') }}" class="text-sm font-medium text-[#00A3DD] hover:underline">View all</a>
        </div>

        @if(isset($assets) && $assets->count())
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($assets as $asset)
                    <div class="bg-white dark:bg-gray-900 rounded-xl ring-1 ring-gray-200 dark:ring-gray-800 overflow-hidden flex flex-col">
                        <div class="bw-flag-stripe" style="height:5px;background:#111111;border-top:2px solid #fff;border-bottom:2px solid #fff;"></div>
                        <div class="p-4 flex-1">
                            <div class="flex items-start justify-between">
                                <h3 class="font-semibold text-gray-900 dark:text-white">{{ $asset->name }}</h3>
                                <span class="text-xs px-2 py-0.5 rounded-full bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">Active</span>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $asset->category }}</p>
                            <dl class="mt-3 space-y-1.5 text-sm">
                                <div class="flex justify-between">
                                    <dt class="text-gray-500 dark:text-gray-400">Amount</dt>
                                    <dd class="font-medium text-gray-900 dark:text-white">
                                        @if($asset->amount_type === 'fixed') @pula($asset->fixed_amount)
                                        @else @pula($asset->min_amount) – @pula($asset->max_amount) @endif
                                    </dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-gray-500 dark:text-gray-400">Return</dt>
                                    <dd class="font-medium text-green-600 dark:text-green-400">
                                        @if($asset->return_type === 'percentage')
                                            {{ rtrim(rtrim(number_format($asset->return_percentage, 2), '0'), '.') }}%
                                        @else @pula($asset->fixed_return) @endif
                                    </dd>
                                </div>
                            </dl>
                        </div>
                        <div class="px-4 pb-4">
                            <a href="{{ route('invest.show', $asset->id) }}"
                               class="block text-center text-white text-sm font-semibold py-2 rounded-lg transition" style="background:#00A3DD;">
                                Invest
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-white dark:bg-gray-900 rounded-xl ring-1 ring-gray-200 dark:ring-gray-800 p-6 text-center text-sm text-gray-500 dark:text-gray-400">
                No assets are available for investment yet. Please check back soon.
            </div>
        @endif
    </div>


    <!-- ============================================================= -->
    <!-- Portfolio activity (signal strength)                          -->
    <!-- ============================================================= -->
    @if(Auth::user()->progress > 2)
    <div class="mb-6 sm:mb-8">
        @php
            $signalStrength = Auth::user()->progress;
            $signalColor = '';
            $signalText = '';
            $signalIcon = '';

            if ($signalStrength < 25) {
                $signalColor = 'from-red-500 to-red-600';
                $signalText = 'Weak Signal';
                $signalIcon = 'signal-low';
            } elseif ($signalStrength >= 25 && $signalStrength < 50) {
                $signalColor = 'from-yellow-500 to-orange-500';
                $signalText = 'Moderate Signal';
                $signalIcon = 'signal-medium';
            } else {
                $signalColor = 'from-green-500 to-emerald-600';
                $signalText = 'Strong Signal';
                $signalIcon = 'signal-high';
            }
        @endphp

        <div class="bg-white dark:bg-gray-900 rounded-xl p-4 sm:p-6 shadow-sm ring-1 ring-gray-200 dark:ring-gray-800">
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center gap-2">
                    <span class="w-8 h-8 rounded-lg flex items-center justify-center bg-[#75AADB]/15 text-[#00A3DD]">
                        <i data-lucide="{{ $signalIcon }}" class="w-4 h-4"></i>
                    </span>
                    <h2 class="text-sm sm:text-base font-semibold text-gray-800 dark:text-gray-100">Portfolio Activity</h2>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-lg sm:text-xl font-bold text-gray-900 dark:text-white">{{ $signalStrength }}%</span>
                    <span class="px-2 py-1 text-xs font-medium rounded-full
                        {{ $signalStrength < 25 ? 'bg-red-100 text-red-700 dark:bg-red-900/20 dark:text-red-400' :
                           ($signalStrength < 50 ? 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/20 dark:text-yellow-400' :
                            'bg-green-100 text-green-700 dark:bg-green-900/20 dark:text-green-400') }}">
                        {{ $signalText }}
                    </span>
                </div>
            </div>

            <div class="w-full h-3 sm:h-4 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden relative">
                <div class="bg-gradient-to-r {{ $signalColor }} h-full rounded-full transition-all duration-700 ease-out relative"
                     style="width: {{ $signalStrength }}%">
                    <div class="absolute inset-0 bg-white/20 animate-pulse rounded-full"></div>
                </div>
            </div>

            <div class="flex justify-between items-center mt-2 text-xs text-gray-500 dark:text-gray-400">
                <span>0% Weak</span>
                <span>25% Moderate</span>
                <span>50%+ Strong</span>
            </div>

            <p class="text-xs text-gray-600 dark:text-gray-400 mt-3 text-center">
                @if($signalStrength < 25)
                    Your portfolio is getting started. Explore available investment plans to grow your holdings.
                @elseif($signalStrength < 50)
                    Your portfolio is developing. Review your investment plans to track progress.
                @else
                    Your investments are active and performing within plan terms.
                @endif
            </p>
        </div>
    </div>
    @endif


    <!-- ============================================================= -->
    <!-- Summary stat cards                                            -->
    <!-- ============================================================= -->
    <div class="grid grid-cols-1 xl:grid-cols-5 gap-4 sm:gap-6 items-stretch mb-6 sm:mb-8">
        <!-- Wallet balance (primary) -->
        <div class="xl:col-span-2 h-full rounded-xl bg-white dark:bg-gray-900 p-4 sm:p-5 lg:p-6 shadow-sm ring-1 ring-gray-200 dark:ring-gray-800 transition-all group relative overflow-hidden" id="balanceCard">
            <!-- subtle flag-stripe accent on top edge -->
            <div class="bw-flag-stripe absolute top-0 inset-x-0" style="height:6px;background:#111111;border-bottom:2px solid #fff;"></div>
            <div class="flex justify-between items-start mb-4 pt-1">
                <div class="text-center sm:text-left w-full sm:w-auto">
                    <h2 class="text-base sm:text-lg font-semibold text-gray-800 dark:text-white flex items-center justify-center sm:justify-start">
                        <span class="w-8 h-8 rounded-lg flex items-center justify-center bg-[#00A3DD]/10 text-[#00A3DD] mr-2">
                            <i data-lucide="wallet" class="w-4 h-4 sm:w-5 sm:h-5"></i>
                        </span>
                        Account Balance
                    </h2>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Your available funds in Pula</p>
                </div>
            </div>

            <div class="flex flex-col">
                <div class="flex items-center justify-center sm:justify-start mb-3">
                    <h3 id="balanceAmount" class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-gray-900 dark:text-white mr-2 break-all">
                        {{ Auth::user()->currency }}{{ number_format(Auth::user()->account_bal, 2, '.', ',') }}
                    </h3>
                    <h3 id="hiddenBalance" class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 dark:text-white mr-2 hidden">••••••</h3>
                </div>

                <div class="inline-flex items-center px-2 py-1 text-xs rounded-full bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 mb-4 w-fit mx-auto sm:mx-0">
                    <i data-lucide="check-circle" class="w-3 h-3 mr-1"></i> Available for Withdrawal
                </div>

                @if(isset($settings->enable_kyc) && $settings->enable_kyc === 'yes')
                    <!-- KYC Status Notification -->
                    <div class="mb-3 w-fit mx-auto sm:mx-0">
                        @if(Auth::user()->account_verify === 'Verified')
                            <div class="inline-flex items-center px-2 py-1 text-xs rounded-full bg-green-50 dark:bg-green-900/20 text-green-600 dark:text-green-400">
                                <i data-lucide="shield-check" class="w-3 h-3 mr-1"></i>
                                <span class="font-medium">Verified Account</span>
                            </div>
                        @elseif(Auth::user()->account_verify === 'Under review')
                            <div class="inline-flex items-center px-2 py-1 text-xs rounded-full bg-yellow-50 dark:bg-yellow-900/20 text-yellow-600 dark:text-yellow-400">
                                <i data-lucide="clock" class="w-3 h-3 mr-1"></i>
                                <span class="font-medium">Under Review</span>
                            </div>
                        @else
                            <div class="inline-flex items-center px-2 py-1 text-xs rounded-full bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400">
                                <i data-lucide="alert-circle" class="w-3 h-3 mr-1"></i>
                                <span class="font-medium">Unverified</span>
                            </div>
                        @endif
                    </div>
                @endif

                <p class="text-xs text-gray-500 dark:text-gray-400 mb-4 text-center sm:text-left">Last updated: {{ now()->format('M d, Y h:i A') }}</p>

                <div class="mt-auto flex flex-col sm:flex-row gap-2">
                    <a href="{{ route('deposits') }}" class="flex items-center justify-center w-full gap-1 text-xs sm:text-sm font-medium px-3 sm:px-4 py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-900 dark:text-white transition">
                        <i data-lucide="plus-circle" class="w-4 h-4"></i> Deposit
                    </a>
                    <a href="{{ route('withdrawalsdeposits') }}" class="flex items-center justify-center w-full gap-1 text-xs sm:text-sm font-medium px-3 sm:px-4 py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-900 dark:text-white transition">
                        <i data-lucide="arrow-up-right" class="w-4 h-4"></i> Withdraw
                    </a>
                </div>
            </div>
        </div>

        <!-- Secondary metric cards -->
        <div class="xl:col-span-3 grid grid-cols-2 lg:grid-cols-4 xl:grid-cols-2 gap-3 sm:gap-4">
            @php
                $cards = [
                    ['label' => 'Total Profit', 'value' => Auth::user()->roi, 'icon' => 'trending-up'],
                    ['label' => 'Total Deposit', 'value' => $deposited, 'icon' => 'arrow-down-circle'],
                    ['label' => 'Total Withdrawal', 'value' => $total_withdrawal, 'icon' => 'arrow-up-circle'],
                    ['label' => 'Bonus', 'value' => Auth::user()->bonus ?? 0, 'icon' => 'coins'],
                ];
            @endphp

            @foreach($cards as $card)
                <div class="rounded-xl bg-white dark:bg-gray-900 p-3 sm:p-4 shadow-sm ring-1 ring-gray-200 dark:ring-gray-800 flex flex-col hover:ring-[#75AADB]/60 transition">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs sm:text-sm text-gray-600 dark:text-gray-400">{{ $card['label'] }}</span>
                        <div class="w-7 h-7 sm:w-9 sm:h-9 flex items-center justify-center rounded-lg bg-[#75AADB]/15 text-[#00A3DD]">
                            <i data-lucide="{{ $card['icon'] }}" class="w-3.5 h-3.5 sm:w-4 sm:h-4"></i>
                        </div>
                    </div>

                    <h3 class="text-base sm:text-xl font-bold text-gray-900 dark:text-white mb-1 truncate">
                        {{ Auth::user()->currency }}{{ number_format($card['value'], 2, '.', ',') }}
                    </h3>

                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-auto flex items-center gap-1">
                        <i data-lucide="calendar" class="w-3 h-3"></i>
                        <span>{{ $card['label'] === 'Total Profit' ? 'Last period' : 'All time' }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>


    <!-- ============================================================= -->
    <!-- KYC verification                                              -->
    <!-- ============================================================= -->
    @if(isset($settings->enable_kyc) && $settings->enable_kyc === 'yes')
        <div class="mb-6 sm:mb-8" x-data="{ kycDropdownOpen: false }" x-cloak>
            @if(Auth::user()->account_verify === 'Verified')
                <!-- Verified Status -->
                <div class="bg-white dark:bg-gray-900 rounded-xl ring-1 ring-gray-200 dark:ring-gray-800 p-4 sm:p-6 shadow-sm">
                    <div class="flex flex-col sm:flex-row items-center gap-4">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-green-50 dark:bg-green-900/20 rounded-xl flex items-center justify-center">
                            <i data-lucide="shield-check" class="w-5 h-5 sm:w-6 sm:h-6 text-green-600 dark:text-green-400"></i>
                        </div>
                        <div class="flex-1 text-center sm:text-left">
                            <h3 class="text-base sm:text-lg font-semibold text-gray-900 dark:text-white mb-1">
                                Account Verified
                            </h3>
                            <p class="text-gray-500 dark:text-gray-400 text-sm">
                                Your identity has been verified. All features are now available.
                            </p>
                        </div>
                        <div class="px-3 py-1 bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-300 rounded-full text-xs font-medium">
                            Verified
                        </div>
                    </div>
                </div>
            @else
                <!-- KYC Verification Needed -->
                <div class="bg-white dark:bg-gray-900 rounded-xl ring-1 ring-gray-200 dark:ring-gray-800 shadow-sm overflow-hidden">
                    <!-- Header -->
                    <div class="p-4 sm:p-6 border-b border-gray-100 dark:border-gray-800">
                        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                            <div class="flex flex-col sm:flex-row items-center gap-4 text-center sm:text-left">
                                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-[#00A3DD]/10 rounded-xl flex items-center justify-center">
                                    <i data-lucide="shield-check" class="w-5 h-5 sm:w-6 sm:h-6 text-[#00A3DD]"></i>
                                </div>
                                <div>
                                    <h3 class="text-base sm:text-lg font-semibold text-gray-900 dark:text-white mb-1">
                                        Identity Verification
                                    </h3>
                                    <p class="text-gray-500 dark:text-gray-400 text-sm">
                                        Complete verification to access all features
                                    </p>
                                </div>
                            </div>

                            <!-- Toggle Button -->
                            <button @click="kycDropdownOpen = !kycDropdownOpen"
                                    class="w-full sm:w-auto px-4 py-2.5 text-white text-sm font-medium rounded-xl transition-colors focus:outline-none focus:ring-2 focus:ring-[#00A3DD]/30 hover:opacity-90"
                                    style="background:#00A3DD;">
                                <span class="flex items-center justify-center gap-2">
                                    <span>View Details</span>
                                    <i data-lucide="chevron-down"
                                       :class="kycDropdownOpen ? 'rotate-180' : 'rotate-0'"
                                       class="w-4 h-4 transition-transform"></i>
                                </span>
                            </button>
                        </div>
                    </div>

                    <!-- Dropdown Content -->
                    <div x-show="kycDropdownOpen"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 -translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 -translate-y-1"
                         class="p-4 sm:p-6 border-t border-gray-100 dark:border-gray-800">

                        @if(Auth::user()->account_verify === 'Under review')
                            <!-- Under Review State -->
                            <div class="text-center space-y-4">
                                <div class="w-16 h-16 mx-auto bg-yellow-50 dark:bg-yellow-900/20 rounded-full flex items-center justify-center">
                                    <i data-lucide="clock" class="w-8 h-8 text-yellow-600 dark:text-yellow-400"></i>
                                </div>
                                <div>
                                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                                        Under Review
                                    </h4>
                                    <p class="text-gray-500 dark:text-gray-400 text-sm max-w-md mx-auto">
                                        Your documents are being reviewed. We'll notify you once the verification is complete.
                                    </p>
                                </div>

                                <!-- Simple Progress -->
                                <div class="max-w-xs mx-auto">
                                    <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400 mb-2">
                                        <span>Submitted</span>
                                        <span>Review</span>
                                        <span>Complete</span>
                                    </div>
                                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-1.5">
                                        <div class="bg-yellow-500 h-1.5 rounded-full w-2/3"></div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <!-- Verification Needed State -->
                            <div class="text-center space-y-6">
                                <div class="w-16 h-16 mx-auto bg-gray-50 dark:bg-gray-800 rounded-full flex items-center justify-center">
                                    <i data-lucide="user-plus" class="w-8 h-8 text-gray-600 dark:text-gray-400"></i>
                                </div>

                                <div>
                                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                                        Complete Your Verification
                                    </h4>
                                    <p class="text-gray-500 dark:text-gray-400 text-sm max-w-md mx-auto mb-6">
                                        Verify your identity to unlock higher limits and enhanced security features.
                                    </p>
                                </div>

                                <!-- Benefits -->
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 max-w-sm mx-auto mb-6">
                                    <div class="text-center p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
                                        <i data-lucide="shield" class="w-5 h-5 mx-auto mb-2 text-gray-600 dark:text-gray-400"></i>
                                        <span class="text-xs text-gray-600 dark:text-gray-400">Enhanced Security</span>
                                    </div>
                                    <div class="text-center p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
                                        <i data-lucide="trending-up" class="w-5 h-5 mx-auto mb-2 text-gray-600 dark:text-gray-400"></i>
                                        <span class="text-xs text-gray-600 dark:text-gray-400">Higher Limits</span>
                                    </div>
                                </div>

                                <!-- Verify Button -->
                                <a href="{{ route('account.verify') }}"
                                   class="inline-flex items-center gap-2 px-6 py-3 text-white font-medium rounded-xl transition-colors focus:outline-none focus:ring-2 focus:ring-[#00A3DD]/30 hover:opacity-90"
                                   style="background:#00A3DD;">
                                    <i data-lucide="user-check" class="w-4 h-4"></i>
                                    <span>Start Verification</span>
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    @endif

    <!-- ============================================================= -->
    <!-- Get started prompt                                            -->
    <!-- ============================================================= -->
    @if($settings->wallet_status == 'on')
        <div class="mb-6 sm:mb-8">
            <div class="relative bg-gray-50 dark:bg-gray-800 rounded-xl p-4 sm:p-6 ring-1 ring-gray-200 dark:ring-gray-700 overflow-hidden">
                <div class="bw-flag-stripe absolute top-0 inset-x-0" style="height:6px;background:#111111;border-bottom:2px solid #fff;"></div>
                <div class="flex flex-col sm:flex-row items-start gap-4 pt-1">
                    <div class="p-3 bg-[#00A3DD]/10 rounded-xl mx-auto sm:mx-0">
                        <i data-lucide="landmark" class="w-6 h-6 sm:w-8 sm:h-8 text-[#00A3DD]"></i>
                    </div>
                    <div class="flex-1 text-center sm:text-left">
                        <h3 class="text-base sm:text-lg font-semibold text-gray-900 dark:text-white mb-2">Invest in the Republic of Botswana</h3>
                        <p class="text-gray-600 dark:text-gray-300 text-sm mb-4">
                            Browse verified national government assets and start earning transparent, regulated returns in Pula.
                        </p>
                        <a href="{{ route('invest.index') }}"
                           class="inline-flex items-center gap-2 px-4 py-2.5 text-white rounded-xl font-medium transition-all duration-200 text-sm hover:opacity-90"
                           style="background:#00A3DD;">
                            <i data-lucide="plus" class="w-4 h-4"></i>
                            View Investment Plans
                        </a>
                    </div>
                    <button onclick="this.parentElement.parentElement.parentElement.style.display='none'"
                            class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 absolute top-3 right-3 sm:relative sm:top-auto sm:right-auto">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- ============================================================= -->
    <!-- Investments overview & quick links                           -->
    <!-- ============================================================= -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-4 sm:gap-6 mb-6 sm:mb-8">
        <div class="xl:col-span-2 bg-white dark:bg-gray-800 rounded-xl shadow-sm ring-1 ring-gray-200 dark:ring-gray-700 p-4 sm:p-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-4 gap-2">
                <h3 class="font-semibold text-base sm:text-lg text-gray-900 dark:text-white flex items-center gap-2">
                    <span class="w-8 h-8 rounded-lg flex items-center justify-center bg-[#00A3DD]/10 text-[#00A3DD]">
                        <i data-lucide="trending-up" class="w-4 h-4"></i>
                    </span>
                    Your investments at a glance
                </h3>
                <a href="{{ route('investments.mine') }}" class="text-[#00A3DD] hover:underline text-sm font-medium text-center sm:text-left">My Portfolio</a>
            </div>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                Welcome to {{ $settings->site_name }}, the official investment platform of the Republic of Botswana.
                Review your holdings and explore the government-backed plans available to you.
            </p>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                <a href="{{ route('investments.mine') }}"
                   class="flex items-center gap-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 hover:bg-gray-100 dark:hover:bg-gray-800 transition p-4">
                    <span class="flex items-center justify-center w-10 h-10 rounded-lg bg-[#00A3DD]/10 text-[#00A3DD]">
                        <i data-lucide="file-text" class="w-5 h-5"></i>
                    </span>
                    <span>
                        <span class="block text-sm font-semibold text-gray-900 dark:text-white">My Portfolio</span>
                        <span class="block text-xs text-gray-500 dark:text-gray-400">View your active investments</span>
                    </span>
                </a>
                <a href="{{ route('invest.index') }}"
                   class="flex items-center gap-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 hover:bg-gray-100 dark:hover:bg-gray-800 transition p-4">
                    <span class="flex items-center justify-center w-10 h-10 rounded-lg bg-[#00A3DD]/10 text-[#00A3DD]">
                        <i data-lucide="landmark" class="w-5 h-5"></i>
                    </span>
                    <span>
                        <span class="block text-sm font-semibold text-gray-900 dark:text-white">Browse Investment Plans</span>
                        <span class="block text-xs text-gray-500 dark:text-gray-400">Explore government-backed plans</span>
                    </span>
                </a>
            </div>
        </div>
        <div class="xl:col-span-1 flex flex-col gap-4 sm:gap-6">
            <!-- Invest with confidence banner -->
            <div class="relative text-white rounded-xl shadow-sm p-5 sm:p-6 text-center flex flex-col items-center justify-center min-h-[120px] overflow-hidden ring-1 ring-black/10">
                <div class="absolute inset-0 bg-center bg-cover" style="background-image:url('{{ asset('images/botswana/baobab.jpg') }}');"></div>
                <div class="absolute inset-0" style="background:linear-gradient(135deg, rgba(11,31,42,.92), rgba(0,163,221,.55));"></div>
                <div class="bw-flag-stripe absolute bottom-0 inset-x-0" style="height:6px;background:#111111;border-top:2px solid #fff;"></div>
                <div class="relative">
                    <i data-lucide="landmark" class="w-9 h-9 sm:w-10 sm:h-10 mb-2 mx-auto"></i>
                    <h3 class="text-base sm:text-lg font-semibold mb-1">Invest with confidence</h3>
                    <p class="text-xs sm:text-sm text-white/80 mb-3">Choose from secure, government-backed investment plans.</p>
                    <a href="{{ route('invest.index') }}" class="inline-block bg-white text-[#0b1f2a] font-semibold px-4 py-2 rounded-lg shadow hover:bg-gray-100 transition">View Plans</a>
                </div>
            </div>
            <!-- Account summary -->
            <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm ring-1 ring-gray-200 dark:ring-gray-700 p-4 sm:p-6">
                <h4 class="text-base sm:text-lg font-semibold text-gray-900 dark:text-white mb-3 flex items-center gap-2">
                    <span class="w-8 h-8 rounded-lg flex items-center justify-center bg-[#00A3DD]/10 text-[#00A3DD]">
                        <i data-lucide="coins" class="w-4 h-4"></i>
                    </span>
                    Account summary
                </h4>
                <ul class="space-y-2 text-sm text-gray-700 dark:text-gray-300">
                    <li class="flex items-center justify-between py-1 border-b border-gray-100 dark:border-gray-800">
                        <span>Available balance</span>
                        <span class="font-semibold text-gray-900 dark:text-white">{{ Auth::user()->currency }}{{ number_format(Auth::user()->account_bal, 2, '.', ',') }}</span>
                    </li>
                    <li class="flex items-center justify-between py-1">
                        <span>Total returns</span>
                        <span class="font-semibold text-gray-900 dark:text-white">{{ Auth::user()->currency }}{{ number_format(Auth::user()->roi, 2, '.', ',') }}</span>
                    </li>
                </ul>
                <a href="{{ route('investments.mine') }}" class="mt-4 block text-center text-sm text-[#00A3DD] font-semibold hover:underline">Go to My Portfolio</a>
            </div>
        </div>
    </div>

    <!-- ============================================================= -->
    <!-- Recent activity & referrals                                  -->
    <!-- ============================================================= -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6 mb-6 sm:mb-8">
        <!-- Recent Activity -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm ring-1 ring-gray-200 dark:ring-gray-700 p-4 sm:p-6">
            <h4 class="font-semibold text-base sm:text-lg mb-4 text-gray-900 dark:text-white flex items-center gap-2">
                <span class="w-8 h-8 rounded-lg flex items-center justify-center bg-[#00A3DD]/10 text-[#00A3DD]">
                    <i data-lucide="file-text" class="w-4 h-4"></i>
                </span>
                Recent Activity
            </h4>
            <div class="overflow-x-auto">
                <table class="min-w-full text-xs sm:text-sm">
                    <thead class="text-gray-500 dark:text-gray-400 border-b border-gray-200 dark:border-gray-700">
                        <tr>
                            <th class="px-2 sm:px-4 py-2 text-left font-medium uppercase tracking-wider text-[11px]">Details</th>
                            <th class="px-2 sm:px-4 py-2 text-left font-medium uppercase tracking-wider text-[11px]">Amount</th>
                            <th class="px-2 sm:px-4 py-2 text-left font-medium uppercase tracking-wider text-[11px]">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @foreach($t_history as $history)
                        <tr class="group hover:bg-gray-50 dark:hover:bg-gray-900 transition">
                            <!-- Activity Details -->
                            <td class="py-3 px-2 sm:px-4 align-top">
                                <div class="flex items-center gap-2">
                                    <span class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-semibold bg-gray-50 text-gray-700 dark:bg-gray-900/40 dark:text-gray-300">
                                        <i data-lucide="file-text" class="w-3 h-3 mr-1"></i>
                                        {{ $history->plan }}
                                    </span>
                                </div>
                                <div class="text-xs text-gray-400 mt-1">{{ $history->created_at->toDayDateTimeString() }}</div>
                            </td>
                            <!-- Amount -->
                            <td class="py-3 px-2 sm:px-4 align-top font-semibold text-gray-900 dark:text-white">
                                {{ Auth::user()->currency }} {{ number_format($history->amount, 2, '.', ',') }}
                            </td>
                            <!-- Status -->
                            <td class="py-3 px-2 sm:px-4 align-top">
                                @if($history->type == 'WIN')
                                    <span class="inline-flex items-center px-2 py-1 rounded-lg bg-green-100 text-green-700 dark:bg-green-900/20 dark:text-green-400 text-xs font-medium">Credited</span>
                                @elseif($history->type == 'LOSE')
                                    <span class="inline-flex items-center px-2 py-1 rounded-lg bg-gray-100 text-gray-700 dark:bg-gray-900/20 dark:text-gray-400 text-xs font-medium">Closed</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-lg bg-[#00A3DD]/10 text-[#00A3DD] text-xs font-medium">{{ $history->type }}</span>
                                @endif
                                <div class="text-xs text-gray-400 mt-1 hidden sm:block">{{ $history->created_at->toDayDateTimeString() }}</div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <a href="{{ route('accounthistory') }}" class="block text-center mt-4 text-[#00A3DD] font-semibold hover:underline">View All</a>
        </div>

        <!-- Referrals -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm ring-1 ring-gray-200 dark:ring-gray-700 p-6 flex flex-col justify-between">
            <div>
                <h4 class="font-semibold text-lg mb-2 text-gray-900 dark:text-white flex items-center gap-2">
                    <span class="w-8 h-8 rounded-lg flex items-center justify-center bg-[#00A3DD]/10 text-[#00A3DD]">
                        <i data-lucide="users" class="w-4 h-4"></i>
                    </span>
                    Referrals
                </h4>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Present our project to your network and enjoy financial benefits. You don't need an active deposit to earn affiliate commissions.</p>
                <a href="{{ route('referuser') }}" class="inline-flex items-center gap-2 text-white px-4 py-2.5 rounded-xl hover:opacity-90 transition text-sm font-medium" style="background:#00A3DD;">
                    <i data-lucide="info" class="w-4 h-4"></i> Learn More
                </a>
                <div class="bg-gray-50 dark:bg-gray-900 rounded-xl ring-1 ring-gray-200 dark:ring-gray-700 p-4 sm:p-6 mt-4">
                    <h4 class="font-semibold mb-2 text-gray-900 dark:text-white text-sm sm:text-base">Personal Referral Link</h4>
                    <div class="flex flex-col sm:flex-row items-stretch gap-2">
                        <input type="text" class="form-input flex-1 rounded-lg border-gray-300 dark:bg-gray-900 dark:border-gray-700 dark:text-white text-gray-900 text-xs sm:text-sm min-w-0" value="{{ Auth::user()->ref_link }}" readonly>
                        <button class="text-white px-4 py-2 rounded-lg text-xs sm:text-sm whitespace-nowrap hover:opacity-90 transition" style="background:#00A3DD;" x-on:click="navigator.clipboard.writeText('{{ Auth::user()->ref_link }}'); showCopied = true">Copy</button>
                    </div>
                    <p x-show="showCopied" class="text-xs sm:text-sm text-green-500 mt-1">Copied to clipboard!</p>
                </div>
            </div>
        </div>
    </div>

    <!-- ============================================================= -->
    <!-- Investment plans call to action                              -->
    <!-- ============================================================= -->
    <div class="relative bg-white dark:bg-gray-800 rounded-xl shadow-sm ring-1 ring-gray-200 dark:ring-gray-700 p-4 sm:p-6 mb-4 sm:mb-6 overflow-hidden">
        <div class="bw-flag-stripe absolute top-0 inset-x-0" style="height:6px;background:#111111;border-bottom:2px solid #fff;"></div>
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pt-1">
            <div class="flex items-start gap-3">
                <span class="flex items-center justify-center w-12 h-12 rounded-xl bg-[#00A3DD]/10 text-[#00A3DD] text-xl">
                    <i data-lucide="landmark" class="w-6 h-6"></i>
                </span>
                <div>
                    <h3 class="font-semibold text-base sm:text-lg text-gray-900 dark:text-white">Government-backed investment plans</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1 max-w-2xl">
                        {{ $settings->site_name }} offers a range of secure, government-backed investment plans designed
                        to help citizens grow their savings. Review the available options and choose the plan that suits you.
                    </p>
                </div>
            </div>
            <a href="{{ route('invest.index') }}"
               class="inline-flex items-center justify-center gap-2 px-5 py-3 text-white rounded-xl font-medium hover:opacity-90 transition whitespace-nowrap"
               style="background:#00A3DD;">
                <i data-lucide="file-text" class="w-4 h-4"></i>
                Browse Investment Plans
            </a>
        </div>
    </div>
</div>
@endsection
