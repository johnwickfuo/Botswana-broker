@extends('layouts.base')

@section('title', $asset->name)

@section('content')
<style>[x-cloak]{display:none!important}</style>
<section class="bg-gray-50 dark:bg-gray-900 min-h-screen py-12">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

        @if (session('error'))
            <div class="mb-6 rounded-lg bg-red-50 border border-red-200 text-red-700 px-4 py-3 text-sm dark:bg-red-900/30 dark:border-red-800 dark:text-red-300">{{ session('error') }}</div>
        @endif
        @if (session('success'))
            <div class="mb-6 rounded-lg bg-green-50 border border-green-200 text-green-700 px-4 py-3 text-sm dark:bg-green-900/30 dark:border-green-800 dark:text-green-300">{{ session('success') }}</div>
        @endif

        <a href="{{ route('invest.index') }}" class="inline-flex items-center text-sm text-blue-600 hover:underline mb-6">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Back to all assets
        </a>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">

                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $asset->name }}</h1>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $asset->category }}</p>
                        </div>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300 capitalize">{{ $asset->status }}</span>
                    </div>
                    @if ($asset->description)
                        <p class="mt-4 text-gray-600 dark:text-gray-300">{{ $asset->description }}</p>
                    @endif

                    <h3 class="mt-6 text-sm font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Investment Terms</h3>
                    <dl class="mt-3 grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                        <div class="flex justify-between border-b border-gray-100 dark:border-gray-700 pb-2">
                            <dt class="text-gray-500 dark:text-gray-400">Investment amount</dt>
                            <dd class="font-medium text-gray-900 dark:text-white">
                                @if ($asset->amount_type === 'fixed') @pula($asset->fixed_amount)
                                @else @pula($asset->min_amount) – @pula($asset->max_amount) @endif
                            </dd>
                        </div>
                        <div class="flex justify-between border-b border-gray-100 dark:border-gray-700 pb-2">
                            <dt class="text-gray-500 dark:text-gray-400">Return</dt>
                            <dd class="font-medium text-green-600 dark:text-green-400">
                                @if ($asset->return_type === 'percentage')
                                    {{ rtrim(rtrim(number_format($asset->return_percentage, 2), '0'), '.') }}%
                                @else @pula($asset->fixed_return) @endif
                            </dd>
                        </div>
                        @if ($asset->duration)
                            <div class="flex justify-between border-b border-gray-100 dark:border-gray-700 pb-2">
                                <dt class="text-gray-500 dark:text-gray-400">Term</dt>
                                <dd class="font-medium text-gray-900 dark:text-white">{{ $asset->duration }} {{ $asset->duration_type }}</dd>
                            </div>
                        @endif
                        <div class="flex justify-between border-b border-gray-100 dark:border-gray-700 pb-2">
                            <dt class="text-gray-500 dark:text-gray-400">Payout frequency</dt>
                            <dd class="font-medium text-gray-900 dark:text-white capitalize">{{ $asset->payout_interval }}</dd>
                        </div>
                    </dl>

                    <h4 class="mt-6 text-sm font-semibold text-gray-700 dark:text-gray-200">Government Certificates</h4>
                    @if ($asset->certificates->isEmpty())
                        <p class="mt-1 text-sm text-gray-400">No certificates published.</p>
                    @else
                        <ul class="mt-2 space-y-2">
                            @foreach ($asset->certificates as $certificate)
                                <li>
                                    <a href="{{ route('invest.certificate', $certificate->id) }}" target="_blank" rel="noopener"
                                        class="inline-flex items-center text-sm text-blue-600 hover:underline">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        {{ $certificate->original_name ?? 'Certificate' }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>

            <!-- Calculator + invest -->
            <div class="lg:col-span-1">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 sticky top-6"
                    x-data="assetCalculator({
                        assetId: {{ $asset->id }},
                        amountType: '{{ $asset->amount_type }}',
                        min: {{ (float) ($asset->min_amount ?? 0) }},
                        max: {{ (float) ($asset->max_amount ?? 0) }},
                        amount: {{ $asset->amount_type === 'ranged' ? (float) ($asset->min_amount ?? 0) : (float) ($asset->fixed_amount ?? 0) }},
                        seed: {{ $initial ? \Illuminate\Support\Js::from([
                            'principal_formatted' => \App\Support\Money::pula($initial['principal']),
                            'return_formatted'    => \App\Support\Money::pula($initial['return']),
                            'total_formatted'     => \App\Support\Money::pula($initial['total']),
                        ]) : 'null' }}
                    })" x-init="init()">

                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Expected Return Calculator</h3>

                    @if ($asset->amount_type === 'ranged')
                        <label class="block mt-4 text-sm text-gray-600 dark:text-gray-300">
                            Investment amount (Pula)
                            <span class="text-xs text-gray-400">— between @pula($asset->min_amount) and @pula($asset->max_amount)</span>
                        </label>
                        <div class="mt-1 relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">P</span>
                            <input type="number" step="0.01" :min="min" :max="max" x-model.number="amount"
                                @input.debounce.300ms="fetchQuote()"
                                class="w-full pl-7 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        </div>
                    @else
                        <p class="mt-4 text-sm text-gray-600 dark:text-gray-300">Fixed investment of <span class="font-semibold text-gray-900 dark:text-white">@pula($asset->fixed_amount)</span>.</p>
                    @endif

                    <div class="mt-5 space-y-3" x-show="result" x-cloak>
                        <div class="flex justify-between text-sm"><span class="text-gray-500 dark:text-gray-400">Principal</span><span class="font-medium text-gray-900 dark:text-white" x-text="result && result.principal_formatted"></span></div>
                        <div class="flex justify-between text-sm"><span class="text-gray-500 dark:text-gray-400">Expected return</span><span class="font-semibold text-green-600 dark:text-green-400" x-text="result && result.return_formatted"></span></div>
                        <div class="flex justify-between text-base border-t border-gray-100 dark:border-gray-700 pt-3"><span class="font-semibold text-gray-700 dark:text-gray-200">Total payout</span><span class="font-bold text-gray-900 dark:text-white" x-text="result && result.total_formatted"></span></div>
                    </div>

                    <p class="mt-4 text-sm text-red-600" x-show="error" x-text="error" x-cloak></p>
                    <p class="mt-3 text-xs text-gray-400" x-show="loading" x-cloak>Calculating…</p>

                    @auth
                        <form method="POST" action="{{ route('investments.store', $asset->id) }}" class="mt-6 border-t border-gray-100 dark:border-gray-700 pt-5">
                            @csrf
                            <input type="hidden" name="amount" :value="amount">
                            <label class="flex items-start gap-2 text-xs text-gray-600 dark:text-gray-300">
                                <input type="checkbox" name="accept_terms" value="1" required class="mt-0.5">
                                <span>I accept the investment terms and understand my funds are <strong>locked until maturity</strong> (no early redemption).</span>
                            </label>
                            <button type="submit" class="mt-3 w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-2.5 rounded-lg transition-colors">Invest now</button>
                        </form>
                    @else
                        <a href="{{ url('login') }}" class="mt-6 block text-center bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 rounded-lg">Log in to invest</a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    function assetCalculator(config) {
        return {
            assetId: config.assetId,
            amountType: config.amountType,
            min: config.min,
            max: config.max,
            amount: config.amount,
            result: config.seed,
            error: '',
            loading: false,
            init() { this.fetchQuote(); },
            fetchQuote() {
                this.loading = true;
                this.error = '';
                let url = '/invest/' + this.assetId + '/quote';
                if (this.amountType === 'ranged') {
                    url += '?amount=' + encodeURIComponent(this.amount);
                }
                fetch(url, { headers: { 'Accept': 'application/json' } })
                    .then(response => response.json())
                    .then(data => {
                        if (data.ok) { this.result = data; this.error = ''; }
                        else { this.error = data.message || 'Please enter a valid amount.'; }
                    })
                    .catch(() => { this.error = 'Unable to calculate right now.'; })
                    .finally(() => { this.loading = false; });
            }
        };
    }
</script>
@endsection
