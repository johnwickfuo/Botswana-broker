@extends('layouts.dasht')
@section('title', $title)
@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Breadcrumb -->
        <nav class="flex items-center text-sm text-gray-500 dark:text-gray-400 mb-6" aria-label="Breadcrumb">
            <a href="{{ route('dashboard') }}" class="hover:text-primary transition-colors">
                <i class="fa-solid fa-house mr-1"></i>
                Dashboard
            </a>
            <i class="fa-solid fa-chevron-right mx-2 text-xs"></i>
            <span class="text-gray-900 dark:text-gray-100 font-medium">Investment Plans</span>
        </nav>

        <!-- Civic Notice -->
        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-sm ring-1 ring-gray-200 dark:ring-gray-800 p-8 sm:p-10 text-center">
            <div class="mx-auto w-16 h-16 flex items-center justify-center rounded-full bg-primary/10 text-primary text-2xl mb-6">
                <i class="fa-solid fa-landmark"></i>
            </div>

            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white mb-3">
                Government-backed investment plans
            </h1>

            <p class="text-gray-600 dark:text-gray-400 text-base sm:text-lg max-w-xl mx-auto mb-8">
                {{ $settings->site_name }} is the official investment platform of the Republic of Botswana.
                Rather than speculative trading, the platform offers a selection of secure, government-backed
                investment plans designed to help citizens grow their savings with confidence.
            </p>

            <a href="{{ route('invest.index') }}"
               class="inline-flex items-center gap-2 bg-primary hover:opacity-90 text-white font-semibold py-3 px-6 rounded-xl transition-colors">
                <i class="fa-solid fa-building-columns"></i>
                Browse Investment Plans
            </a>
        </div>
    </div>
</div>
@endsection
