@extends('layouts.base')

@section('title', 'Investment Plans')

@section('content')

<!-- Hero band -->
<section class="relative text-white">
    <div class="absolute inset-0 bg-center bg-cover" style="background-image: linear-gradient(rgba(17,17,17,.74), rgba(17,17,17,.8)), url('{{ asset('images/botswana/landscape.jpg') }}');"></div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 text-center">
        <div class="inline-flex items-center gap-3 mb-4">
            <img src="{{ asset('images/botswana/flag.svg') }}" alt="Flag of Botswana" class="h-6 w-auto rounded-sm shadow">
            <span class="text-xs font-semibold uppercase tracking-widest" style="color:#75AADB">Republic of Botswana</span>
        </div>
        <h1 class="bw-display text-3xl sm:text-4xl">Government Investment Plans</h1>
        <p class="mt-3 text-gray-200 max-w-2xl mx-auto">Invest in verified national assets of the Republic of Botswana. All amounts in Pula (P).</p>
    </div>
    <div class="bw-flag-stripe"></div>
</section>

<section class="bg-gray-50 dark:bg-gray-900 min-h-screen py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        @if ($plans->isEmpty())
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-10 text-center text-gray-500 dark:text-gray-400">
                There are no active investment plans available at the moment. Please check back soon.
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($plans as $plan)
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-lg transition-shadow border border-gray-100 dark:border-gray-700 flex flex-col">
                        <div class="p-6 flex-1">
                            <div class="flex items-center justify-between">
                                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $plan->name }}</h2>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">Active</span>
                            </div>

                            @if ($plan->asset)
                                <div class="mt-3 flex items-start gap-2 text-sm text-gray-600 dark:text-gray-300">
                                    <svg class="w-5 h-5 text-blue-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <span><span class="font-medium text-gray-800 dark:text-gray-100">{{ $plan->asset->name }}</span>
                                        <span class="text-gray-400">·</span> {{ $plan->asset->category }}</span>
                                </div>
                            @endif

                            @if ($plan->description)
                                <p class="mt-3 text-sm text-gray-500 dark:text-gray-400 line-clamp-3">{{ \Illuminate\Support\Str::limit($plan->description, 120) }}</p>
                            @endif

                            <dl class="mt-5 space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <dt class="text-gray-500 dark:text-gray-400">Investment</dt>
                                    <dd class="font-medium text-gray-900 dark:text-white">
                                        @if ($plan->amount_type === 'fixed')
                                            @pula($plan->fixed_amount)
                                        @else
                                            @pula($plan->min_amount) – @pula($plan->max_amount)
                                        @endif
                                    </dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-gray-500 dark:text-gray-400">Return</dt>
                                    <dd class="font-medium text-green-600 dark:text-green-400">
                                        @if ($plan->return_type === 'percentage')
                                            {{ rtrim(rtrim(number_format($plan->return_percentage, 2), '0'), '.') }}%
                                        @else
                                            @pula($plan->fixed_return)
                                        @endif
                                    </dd>
                                </div>
                                @if ($plan->duration)
                                    <div class="flex justify-between">
                                        <dt class="text-gray-500 dark:text-gray-400">Duration</dt>
                                        <dd class="font-medium text-gray-900 dark:text-white">{{ $plan->duration }} {{ $plan->duration_type }}</dd>
                                    </div>
                                @endif
                            </dl>
                        </div>
                        <div class="px-6 pb-6">
                            <a href="{{ route('invest.show', $plan->id) }}"
                                class="block w-full text-center bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 rounded-lg transition-colors">
                                View details &amp; calculate return
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>
@endsection
