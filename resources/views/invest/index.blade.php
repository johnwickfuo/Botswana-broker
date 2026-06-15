@extends('layouts.base')

@section('title', 'Invest in National Assets')

@section('content')

<!-- Hero band -->
<section class="relative text-white">
    <div class="absolute inset-0 bg-center bg-cover" style="background-image: linear-gradient(rgba(17,17,17,.74), rgba(17,17,17,.8)), url('{{ asset('images/botswana/landscape.jpg') }}');"></div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 text-center">
        <div class="inline-flex items-center gap-3 mb-4">
            <img src="{{ asset('images/botswana/flag.svg') }}" alt="Flag of Botswana" class="h-6 w-auto rounded-sm shadow">
            <span class="text-xs font-semibold uppercase tracking-widest" style="color:#75AADB">Republic of Botswana</span>
        </div>
        <h1 class="bw-display text-3xl sm:text-4xl">Invest in National Assets</h1>
        <p class="mt-3 text-gray-200 max-w-2xl mx-auto">Choose a verified government asset and invest directly. All amounts in Pula (P).</p>
    </div>
    <div class="bw-flag-stripe"></div>
</section>

<section class="bg-gray-50 dark:bg-gray-900 min-h-screen py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        @if ($assets->isEmpty())
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-10 text-center text-gray-500 dark:text-gray-400">
                There are no assets available for investment at the moment. Please check back soon.
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($assets as $asset)
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-lg transition-shadow border border-gray-100 dark:border-gray-700 flex flex-col overflow-hidden">
                        <div class="bw-flag-stripe" style="height:6px;border-width:2px"></div>
                        <div class="p-6 flex-1">
                            <div class="flex items-center justify-between">
                                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $asset->name }}</h2>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">Active</span>
                            </div>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $asset->category }}</p>

                            @if ($asset->description)
                                <p class="mt-3 text-sm text-gray-500 dark:text-gray-400 line-clamp-3">{{ \Illuminate\Support\Str::limit($asset->description, 120) }}</p>
                            @endif

                            <dl class="mt-5 space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <dt class="text-gray-500 dark:text-gray-400">Investment</dt>
                                    <dd class="font-medium text-gray-900 dark:text-white">
                                        @if ($asset->amount_type === 'fixed')
                                            @pula($asset->fixed_amount)
                                        @else
                                            @pula($asset->min_amount) – @pula($asset->max_amount)
                                        @endif
                                    </dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-gray-500 dark:text-gray-400">Return</dt>
                                    <dd class="font-medium text-green-600 dark:text-green-400">
                                        @if ($asset->return_type === 'percentage')
                                            {{ rtrim(rtrim(number_format($asset->return_percentage, 2), '0'), '.') }}%
                                        @else
                                            @pula($asset->fixed_return)
                                        @endif
                                    </dd>
                                </div>
                                @if ($asset->duration)
                                    <div class="flex justify-between">
                                        <dt class="text-gray-500 dark:text-gray-400">Term</dt>
                                        <dd class="font-medium text-gray-900 dark:text-white">{{ $asset->duration }} {{ $asset->duration_type }}, paid {{ $asset->payout_interval }}</dd>
                                    </div>
                                @endif
                            </dl>
                        </div>
                        <div class="px-6 pb-6">
                            <a href="{{ route('invest.show', $asset->id) }}"
                                class="block w-full text-center bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 rounded-lg transition-colors">
                                View &amp; invest
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>
@endsection
