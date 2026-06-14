@extends('layouts.base')

@section('title', 'My Portfolio')

@section('content')
<section class="bg-gray-50 dark:bg-gray-900 min-h-screen py-12">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

        <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">My Portfolio</h1>

        @if (session('error'))
            <div class="mb-6 rounded-lg bg-red-50 border border-red-200 text-red-700 px-4 py-3 text-sm dark:bg-red-900/30 dark:border-red-800 dark:text-red-300">{{ session('error') }}</div>
        @endif
        @if (session('success'))
            <div class="mb-6 rounded-lg bg-green-50 border border-green-200 text-green-700 px-4 py-3 text-sm dark:bg-green-900/30 dark:border-green-800 dark:text-green-300">{{ session('success') }}</div>
        @endif

        {{-- Portfolio summary --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
                <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">Active invested</p>
                <p class="mt-1 text-xl font-bold text-gray-900 dark:text-white">@pula($summary['invested'])</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
                <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">Expected return</p>
                <p class="mt-1 text-xl font-bold text-green-600 dark:text-green-400">@pula($summary['expected'])</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
                <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">Earned to date</p>
                <p class="mt-1 text-xl font-bold text-green-600 dark:text-green-400">@pula($summary['earned'])</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
                <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">Active investments</p>
                <p class="mt-1 text-xl font-bold text-gray-900 dark:text-white">{{ $summary['active_count'] }}</p>
            </div>
        </div>

        @if ($investments->isEmpty())
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-10 text-center text-gray-500 dark:text-gray-400">
                You have no investments yet. <a href="{{ route('invest.index') }}" class="text-blue-600 hover:underline">Browse plans</a>.
            </div>
        @else
            <div class="space-y-4">
                @foreach ($investments as $investment)
                    @php($nextPayout = $investment->payouts->where('status', 'pending')->sortBy('due_date')->first())
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
                        <div class="flex flex-wrap items-start justify-between gap-4">
                            <div>
                                <h2 class="font-semibold text-gray-900 dark:text-white">{{ optional($investment->investmentPlan)->name ?? 'Plan' }}</h2>
                                @if (optional($investment->investmentPlan)->asset)
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $investment->investmentPlan->asset->name }}</p>
                                @endif
                            </div>
                            <div>
                                @if ($investment->status === 'active')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300">Active</span>
                                @elseif ($investment->status === 'matured')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">Matured</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-700 capitalize">{{ $investment->status }}</span>
                                @endif
                            </div>
                        </div>

                        <dl class="mt-4 grid grid-cols-2 sm:grid-cols-5 gap-4 text-sm">
                            <div>
                                <dt class="text-gray-500 dark:text-gray-400">Principal</dt>
                                <dd class="font-medium text-gray-900 dark:text-white">@pula($investment->invested_amount)</dd>
                            </div>
                            <div>
                                <dt class="text-gray-500 dark:text-gray-400">Expected return</dt>
                                <dd class="font-medium text-gray-900 dark:text-white">@pula($investment->expected_return)</dd>
                            </div>
                            <div>
                                <dt class="text-gray-500 dark:text-gray-400">Earned</dt>
                                <dd class="font-medium text-green-600 dark:text-green-400">@pula($investment->total_profit)</dd>
                            </div>
                            <div>
                                <dt class="text-gray-500 dark:text-gray-400">Start</dt>
                                <dd class="font-medium text-gray-900 dark:text-white">{{ optional($investment->start_date)->format('d M Y') ?? '—' }}</dd>
                            </div>
                            <div>
                                <dt class="text-gray-500 dark:text-gray-400">Maturity</dt>
                                <dd class="font-medium text-gray-900 dark:text-white">{{ optional($investment->maturity_date)->format('d M Y') ?? '—' }}</dd>
                            </div>
                        </dl>

                        <div class="mt-4 flex flex-wrap items-center gap-4">
                            @if ($investment->status === 'active')
                                @if ($nextPayout)
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        Next {{ $nextPayout->type === 'principal' ? 'principal repayment' : 'return payout' }}:
                                        <span class="font-medium">@pula($nextPayout->amount)</span> on {{ optional($nextPayout->due_date)->format('d M Y') }}
                                    </p>
                                @endif
                                @if ($investment->canRedeem())
                                    <form method="POST" action="{{ route('investments.redeem', $investment->id) }}">
                                        @csrf
                                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white text-sm font-medium px-4 py-2 rounded-lg">
                                            Redeem now
                                        </button>
                                    </form>
                                @else
                                    <span class="text-xs text-gray-400">
                                        <svg class="inline w-4 h-4 -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                        Locked until {{ optional($investment->maturity_date)->format('d M Y') }}
                                    </span>
                                @endif
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6">{{ $investments->links() }}</div>
        @endif
    </div>
</section>
@endsection
