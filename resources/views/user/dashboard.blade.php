@extends('layouts.dasht')
@section('title', $title)
@section('content')
<div class="container mx-auto px-3 sm:px-4 lg:px-6 py-4 sm:py-6 lg:py-8" x-data="{ showCopied: false }">

    <x-danger-alert />
    <x-success-alert />
    <x-notify-alert />

    <!-- Dashboard Header -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-6 sm:mb-8 gap-4">
        <div class="text-center lg:text-left">
            <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900 dark:text-white">Welcome back, {{ Auth::user()->name }}!</h1>
            <p class="text-sm sm:text-base text-gray-500 dark:text-gray-400 mt-1">Your investment dashboard overview</p>
        </div>
        <div class="hidden sm:flex flex-col sm:flex-row gap-2 sm:gap-3">
            @if($settings->wallet_status == "on")
                <a href="#" class="inline-flex items-center justify-center gap-2 px-4 py-2 sm:py-3 bg-gradient-to-r from-indigo-600 to-blue-500 text-white rounded-lg shadow hover:from-indigo-700 transition animate-pulse text-sm sm:text-base">
                    <i data-lucide="link" class="w-4 h-4 sm:w-5 sm:h-5"></i> Connect Wallet
                </a>
            @else
                <div class="inline-flex items-center justify-center gap-2 px-4 py-2 sm:py-3 bg-green-100 dark:bg-green-900/20 text-green-700 dark:text-green-300 rounded-lg text-sm sm:text-base">
                    <i data-lucide="check-circle" class="w-4 h-4 sm:w-5 sm:h-5"></i> Wallet Connected
                </div>
            @endif
            <a href="{{ route('invest.index') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2 sm:py-3 bg-primary text-white rounded-lg shadow hover:opacity-90 transition text-sm sm:text-base">
                <i class="fa-solid fa-building-columns"></i> Browse Plans
            </a>
        </div>
    </div>




    <!-- Signal Strength -->
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
                    <i data-lucide="{{ $signalIcon }}" class="w-5 h-5 text-gray-600 dark:text-gray-300"></i>
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


 <!-- Investment Dashboard - Clean Modern Layout -->
<div class="grid grid-cols-1 xl:grid-cols-5 gap-4 sm:gap-6 items-stretch mb-6 sm:mb-8">
    <!-- Account Balance -->
    <div class="xl:col-span-2 h-full rounded-2xl bg-white dark:bg-gray-900 p-4 sm:p-5 lg:p-6 shadow-sm ring-1 ring-gray-200 dark:ring-gray-800 transition-all group" id="balanceCard">
        <div class="flex justify-between items-start mb-4">
            <div class="text-center sm:text-left w-full sm:w-auto">
                <h2 class="text-base sm:text-lg font-semibold text-gray-800 dark:text-white flex items-center justify-center sm:justify-start">
                    <i data-lucide="wallet" class="w-4 h-4 sm:w-5 sm:h-5 mr-2 text-gray-500 dark:text-gray-300"></i>
                    Account Balance
                </h2>
                <p class="text-xs text-gray-500 dark:text-gray-400">Your available funds</p>
            </div>
            {{-- <button id="toggleBalanceVisibility" class="text-gray-400 hover:text-gray-700 dark:hover:text-white">
                <i data-lucide="eye" class="h-5 w-5" id="visibilityIcon"></i>
            </button> --}}
        </div>

        <div class="flex flex-col">
            <div class="flex items-center justify-center sm:justify-start mb-3">
                <h3 id="balanceAmount" class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 dark:text-white mr-2 break-all">
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
                        <div class="inline-flex items-center px-2 py-1 text-xs rounded-full bg-green-50 dark:bg-green-900/20 text-green-600 dark:text-green-400 animate-pulse">
                            <i data-lucide="shield-check" class="w-3 h-3 mr-1"></i>
                            <span class="font-medium">Verified Account</span>
                        </div>
                    @elseif(Auth::user()->account_verify === 'Under review')
                        <div class="inline-flex items-center px-2 py-1 text-xs rounded-full bg-yellow-50 dark:bg-yellow-900/20 text-yellow-600 dark:text-yellow-400 animate-pulse">
                            <i data-lucide="clock" class="w-3 h-3 mr-1"></i>
                            <span class="font-medium">Under Review</span>
                        </div>
                    @else
                        <div class="inline-flex items-center px-2 py-1 text-xs rounded-full bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 animate-pulse">
                            <i data-lucide="alert-circle" class="w-3 h-3 mr-1"></i>
                            <span class="font-medium">Unverified</span>
                        </div>
                    @endif
                </div>
            @endif

            <p class="text-xs text-gray-500 dark:text-gray-400 mb-4 text-center sm:text-left">Last updated: {{ now()->format('M d, Y h:i A') }}</p>

            <div class="mt-auto flex flex-col sm:flex-row gap-2">
                <a href="{{ route('deposits') }}" class="flex items-center justify-center w-full gap-1 text-xs sm:text-sm font-medium px-3 sm:px-4 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-900 dark:text-white transition">
                    <i data-lucide="plus-circle" class="w-4 h-4"></i> Deposit
                </a>
                <a href="{{ route('withdrawalsdeposits') }}" class="flex items-center justify-center w-full gap-1 text-xs sm:text-sm font-medium px-3 sm:px-4 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-900 dark:text-white transition">
                    <i data-lucide="arrow-up-right" class="w-4 h-4"></i> Withdraw
                </a>
            </div>
        </div>
    </div>

    <!-- Secondary Metrics -->
    <div class="xl:col-span-3 grid grid-cols-2 lg:grid-cols-4 xl:grid-cols-2 gap-3 sm:gap-4">
        @php
            $cards = [
                ['label' => 'Total Profit', 'value' => Auth::user()->roi, 'icon' => 'dollar-sign'],
                ['label' => 'Total Deposit', 'value' => $deposited, 'icon' => 'arrow-down'],
                ['label' => 'Total Withdrawal', 'value' => $total_withdrawal, 'icon' => 'arrow-up'],
                ['label' => 'Bonus', 'value' => Auth::user()->bonus ?? 0, 'icon' => 'gift'],
            ];
        @endphp

        @foreach($cards as $card)
            <div class="rounded-2xl bg-white dark:bg-gray-900 p-3 sm:p-4 shadow-sm ring-1 ring-gray-200 dark:ring-gray-800 flex flex-col">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs sm:text-sm text-gray-600 dark:text-gray-400">{{ $card['label'] }}</span>
                    <div class="w-6 h-6 sm:w-8 sm:h-8 flex items-center justify-center rounded-full bg-gray-100 dark:bg-gray-800">
                        <i data-lucide="{{ $card['icon'] }}" class="w-3 h-3 sm:w-4 sm:h-4 text-gray-500 dark:text-gray-300"></i>
                    </div>
                </div>

                <h3 class="text-sm sm:text-lg font-semibold text-gray-900 dark:text-white mb-1 truncate">
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




    @if(isset($settings->enable_kyc) && $settings->enable_kyc === 'yes')
        <!-- KYC Verification Component -->
        <div class="mb-6 sm:mb-8" x-data="{ kycDropdownOpen: false }" x-cloak>
            @if(Auth::user()->account_verify === 'Verified')
                <!-- Verified Status -->
                <div class="bg-white dark:bg-gray-900 rounded-lg border border-gray-100 dark:border-gray-800 p-4 sm:p-6 shadow-sm">
                    <div class="flex flex-col sm:flex-row items-center gap-4">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-green-50 dark:bg-green-900/20 rounded-lg flex items-center justify-center">
                            <i data-lucide="check-circle" class="w-5 h-5 sm:w-6 sm:h-6 text-green-600 dark:text-green-400"></i>
                        </div>
                        <div class="flex-1 text-center sm:text-left">
                            <h3 class="text-base sm:text-lg font-medium text-gray-900 dark:text-white mb-1">
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
                <div class="bg-white dark:bg-gray-900 rounded-lg border border-gray-100 dark:border-gray-800 shadow-sm">
                    <!-- Header -->
                    <div class="p-4 sm:p-6 border-b border-gray-100 dark:border-gray-800">
                        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                            <div class="flex flex-col sm:flex-row items-center gap-4 text-center sm:text-left">
                                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-blue-50 dark:bg-blue-900/20 rounded-lg flex items-center justify-center">
                                    <i data-lucide="shield-check" class="w-5 h-5 sm:w-6 sm:h-6 text-blue-600 dark:text-blue-400"></i>
                                </div>
                                <div>
                                    <h3 class="text-base sm:text-lg font-medium text-gray-900 dark:text-white mb-1">
                                        Identity Verification
                                    </h3>
                                    <p class="text-gray-500 dark:text-gray-400 text-sm">
                                        Complete verification to access all features
                                    </p>
                                </div>
                            </div>

                            <!-- Toggle Button -->
                            <button @click="kycDropdownOpen = !kycDropdownOpen"
                                    class="w-full sm:w-auto px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500/20">
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
                                    <h4 class="text-lg font-medium text-gray-900 dark:text-white mb-2">
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
                                    <h4 class="text-lg font-medium text-gray-900 dark:text-white mb-2">
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
                                   class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500/20">
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

 @if($settings->wallet_status == 'on')
        <!-- Get started prompt -->
        <div class="mb-6 sm:mb-8">
            <div class="bg-gray-50 dark:bg-gray-800 rounded-2xl p-4 sm:p-6 border border-gray-200 dark:border-gray-700">
                <div class="flex flex-col sm:flex-row items-start gap-4">
                    <div class="p-3 bg-blue-50 dark:bg-blue-900/30 rounded-xl mx-auto sm:mx-0">
                        <i data-lucide="landmark" class="w-6 h-6 sm:w-8 sm:h-8 text-blue-600 dark:text-blue-400"></i>
                    </div>
                    <div class="flex-1 text-center sm:text-left">
                        <h3 class="text-base sm:text-lg font-semibold text-gray-900 dark:text-white mb-2">Invest in the Republic of Botswana</h3>
                        <p class="text-gray-600 dark:text-gray-300 text-sm mb-4">
                            Browse verified national government assets and start earning transparent, regulated returns in Pula.
                        </p>
                        <a href="{{ route('invest.index') }}"
                           class="inline-flex items-center gap-2 px-4 py-2 sm:py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-all duration-200 text-sm sm:text-base">
                            <i data-lucide="plus" class="w-4 h-4"></i>
                            View Investment Plans
                        </a>
                    </div>
                    <button onclick="this.parentElement.parentElement.parentElement.style.display='none'"
                            class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 absolute top-2 right-2 sm:relative sm:top-auto sm:right-auto">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>
            </div>
        </div>
    @endif



 <!-- Quick Actions Grid (Tinker UI, Mature/Neutral) -->
{{-- <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-8">
    <a href="{{ route('deposits') }}" class="flex flex-col items-center justify-center rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 hover:bg-gray-50 dark:hover:bg-gray-800 transition shadow-sm group py-3 px-2">
        <span class="flex items-center justify-center w-9 h-9 rounded-lg bg-gray-100 dark:bg-gray-800 mb-1">
            <i data-lucide="plus-circle" class="w-5 h-5 text-gray-600 dark:text-gray-300"></i>
        </span>
        <span class="font-medium text-xs text-gray-800 dark:text-gray-200">Deposit</span>
    </a>
    <a href="{{ route('withdrawalsdeposits') }}" class="flex flex-col items-center justify-center rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 hover:bg-gray-50 dark:hover:bg-gray-800 transition shadow-sm group py-3 px-2">
        <span class="flex items-center justify-center w-9 h-9 rounded-lg bg-gray-100 dark:bg-gray-800 mb-1">
            <i data-lucide="arrow-up-right" class="w-5 h-5 text-gray-600 dark:text-gray-300"></i>
        </span>
        <span class="font-medium text-xs text-gray-800 dark:text-gray-200">Withdraw</span>
    </a>
    <a href="{{ route('mplans') }}" class="flex flex-col items-center justify-center rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 hover:bg-gray-50 dark:hover:bg-gray-800 transition shadow-sm group py-3 px-2">
        <span class="flex items-center justify-center w-9 h-9 rounded-lg bg-gray-100 dark:bg-gray-800 mb-1">
            <i data-lucide="trending-up" class="w-5 h-5 text-gray-600 dark:text-gray-300"></i>
        </span>
        <span class="font-medium text-xs text-gray-800 dark:text-gray-200">Invest</span>
    </a>
    <a href="#" class="flex flex-col items-center justify-center rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 hover:bg-gray-50 dark:hover:bg-gray-800 transition shadow-sm group py-3 px-2">
        <span class="flex items-center justify-center w-9 h-9 rounded-lg bg-gray-100 dark:bg-gray-800 mb-1">
            <i data-lucide="refresh-ccw" class="w-5 h-5 text-gray-600 dark:text-gray-300"></i>
        </span>
        <span class="font-medium text-xs text-gray-800 dark:text-gray-200">Swap</span>
    </a>
</div> --}}







    <!-- Investments Overview & Quick Links -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-4 sm:gap-6 mb-6 sm:mb-8">
        <div class="xl:col-span-2 bg-white dark:bg-gray-800 rounded-xl shadow p-4 sm:p-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-4 gap-2">
                <h3 class="font-semibold text-base sm:text-lg text-gray-900 dark:text-white flex items-center gap-2">
                    <i class="fa-solid fa-chart-pie text-primary"></i>
                    Your investments at a glance
                </h3>
                <a href="{{ route('investments.mine') }}" class="text-primary hover:underline text-sm text-center sm:text-left">My Portfolio</a>
            </div>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                Welcome to {{ $settings->site_name }}, the official investment platform of the Republic of Botswana.
                Review your holdings and explore the government-backed plans available to you.
            </p>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                <a href="{{ route('investments.mine') }}"
                   class="flex items-center gap-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 hover:bg-gray-100 dark:hover:bg-gray-800 transition p-4">
                    <span class="flex items-center justify-center w-10 h-10 rounded-lg bg-primary/10 text-primary">
                        <i class="fa-solid fa-file-contract"></i>
                    </span>
                    <span>
                        <span class="block text-sm font-semibold text-gray-900 dark:text-white">My Portfolio</span>
                        <span class="block text-xs text-gray-500 dark:text-gray-400">View your active investments</span>
                    </span>
                </a>
                <a href="{{ route('invest.index') }}"
                   class="flex items-center gap-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 hover:bg-gray-100 dark:hover:bg-gray-800 transition p-4">
                    <span class="flex items-center justify-center w-10 h-10 rounded-lg bg-primary/10 text-primary">
                        <i class="fa-solid fa-building-columns"></i>
                    </span>
                    <span>
                        <span class="block text-sm font-semibold text-gray-900 dark:text-white">Browse Investment Plans</span>
                        <span class="block text-xs text-gray-500 dark:text-gray-400">Explore government-backed plans</span>
                    </span>
                </a>
            </div>
        </div>
        <div class="xl:col-span-1 flex flex-col gap-4 sm:gap-6">
            <div class="bg-primary text-white rounded-xl shadow p-4 sm:p-6 text-center flex flex-col items-center justify-center min-h-[120px]">
                <i class="fa-solid fa-landmark text-3xl sm:text-4xl mb-2"></i>
                <h3 class="text-base sm:text-lg font-semibold mb-1">Invest with confidence</h3>
                <p class="text-xs sm:text-sm mb-3">Choose from secure, government-backed investment plans.</p>
                <a href="{{ route('invest.index') }}" class="inline-block bg-white text-primary font-semibold px-4 py-2 rounded-lg shadow hover:bg-gray-100 transition">View Plans</a>
            </div>
            <div class="bg-white dark:bg-gray-900 rounded-2xl shadow ring-1 ring-gray-200 dark:ring-gray-700 p-4 sm:p-6">
                <h4 class="text-base sm:text-lg font-semibold text-gray-900 dark:text-white mb-2 flex items-center gap-2">
                    <i class="fa-solid fa-coins text-primary"></i>
                    Account summary
                </h4>
                <ul class="space-y-2 text-sm text-gray-700 dark:text-gray-300">
                    <li class="flex items-center justify-between">
                        <span>Available balance</span>
                        <span class="font-semibold text-gray-900 dark:text-white">{{ Auth::user()->currency }}{{ number_format(Auth::user()->account_bal, 2, '.', ',') }}</span>
                    </li>
                    <li class="flex items-center justify-between">
                        <span>Total returns</span>
                        <span class="font-semibold text-gray-900 dark:text-white">{{ Auth::user()->currency }}{{ number_format(Auth::user()->roi, 2, '.', ',') }}</span>
                    </li>
                </ul>
                <a href="{{ route('investments.mine') }}" class="mt-4 block text-center text-sm text-primary font-semibold hover:underline">Go to My Portfolio</a>
            </div>
        </div>
    </div>

    <!-- Recent Activity & Referrals -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6 mb-6 sm:mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4 sm:p-6">
            <h4 class="font-semibold text-base sm:text-lg mb-3 text-gray-900 dark:text-white flex items-center gap-2">
                <i class="fa-solid fa-file-contract text-primary"></i>
                Recent Activity
            </h4>
            <div class="overflow-x-auto">
                <table class="min-w-full text-xs sm:text-sm">
                    <thead class="text-gray-700 dark:text-gray-200">
                        <tr>
                            <th class="px-2 sm:px-4 py-2 text-left">Details</th>
                            <th class="px-2 sm:px-4 py-2">Amount</th>
                            <th class="px-2 sm:px-4 py-2">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($t_history as $history)
                        <tr class="group hover:bg-gray-50 dark:hover:bg-gray-900 transition">
                            <!-- Activity Details -->
                            <td class="py-3 px-2 sm:px-4 align-top">
                                <div class="flex items-center gap-2">
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-semibold bg-gray-50 text-gray-700 dark:bg-gray-900/20 dark:text-gray-300">
                                        <i class="fa-solid fa-file-contract mr-1"></i>
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
                                    <span class="inline-flex items-center px-2 py-1 rounded bg-green-100 text-green-700 dark:bg-green-900/20 dark:text-green-400 text-xs font-medium">Credited</span>
                                @elseif($history->type == 'LOSE')
                                    <span class="inline-flex items-center px-2 py-1 rounded bg-gray-100 text-gray-700 dark:bg-gray-900/20 dark:text-gray-400 text-xs font-medium">Closed</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded bg-blue-100 text-blue-700 dark:bg-blue-900/20 dark:text-blue-400 text-xs font-medium">{{ $history->type }}</span>
                                @endif
                                <div class="text-xs text-gray-400 mt-1 hidden sm:block">{{ $history->created_at->toDayDateTimeString() }}</div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <a href="{{ route('accounthistory') }}" class="block text-center mt-4 text-blue-600 font-semibold">View All</a>
        </div>


        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 flex flex-col justify-between">
            <div>
                <h4 class="font-semibold text-lg mb-2 text-gray-900 dark:text-white">Referrals</h4>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Present our project to your network and enjoy financial benefits. You don’t need an active deposit to earn affiliate commissions.</p>
                <a href="{{ route('referuser') }}" class="inline-block bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">Learn More</a>
             <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4 sm:p-6 mt-4">
                <h4 class="font-semibold mb-2 text-gray-900 dark:text-white text-sm sm:text-base">Personal Referral Link</h4>
                <div class="flex flex-col sm:flex-row items-stretch gap-2">
                    <input type="text" class="form-input flex-1 rounded border-gray-300 dark:bg-gray-900 dark:border-gray-700 text-white text-xs sm:text-sm min-w-0" value="{{ Auth::user()->ref_link }}" readonly>
                    <button class="bg-blue-600 text-white px-4 py-2 rounded text-xs sm:text-sm whitespace-nowrap" x-on:click="navigator.clipboard.writeText('{{ Auth::user()->ref_link }}'); showCopied = true">Copy</button>
                </div>
                <p x-show="showCopied" class="text-xs sm:text-sm text-green-500 mt-1">Copied to clipboard!</p>
            </div>

            </div>

        </div>
    </div>
  <!-- Investment Plans Call to Action -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4 sm:p-6 mb-4 sm:mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-start gap-3">
                <span class="flex items-center justify-center w-12 h-12 rounded-xl bg-primary/10 text-primary text-xl">
                    <i class="fa-solid fa-building-columns"></i>
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
               class="inline-flex items-center justify-center gap-2 px-5 py-3 bg-primary text-white rounded-lg font-medium hover:opacity-90 transition whitespace-nowrap">
                <i class="fa-solid fa-file-contract"></i>
                Browse Investment Plans
            </a>
        </div>
    </div>
</div>
@endsection
