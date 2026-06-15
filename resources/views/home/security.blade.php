@extends('layouts.base')

@section('title', 'Our Services')

@section('content')
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto">
            <p class="text-sm font-semibold uppercase tracking-widest" style="color:#00A3DD">{{ $settings->site_name }}</p>
            <h1 class="mt-3 text-3xl font-bold text-gray-900">Investing in the Republic of Botswana</h1>
            <p class="mt-4 text-gray-600">
                {{ $settings->site_name }} enables citizens to invest in verified national government assets, with transparent terms and regulated returns paid in Pula.
            </p>
            <div class="bw-divider mx-auto w-24"></div>
        </div>

        <div class="mt-14 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @php($services = [
                ['fa-mountain', 'Mining &amp; Minerals', 'Invest in the nation\'s strategic mineral and mining assets.'],
                ['fa-bolt', 'Energy', 'Support power generation and national energy infrastructure.'],
                ['fa-road', 'Infrastructure', 'Roads, transport and public works that serve the Republic.'],
                ['fa-faucet-drip', 'Water &amp; Sanitation', 'Water supply and sanitation assets securing the nation\'s future.'],
                ['fa-wheat-awn', 'Agriculture', 'Agricultural and food-security projects across Botswana.'],
                ['fa-building-columns', 'Public Institutions', 'State enterprises and verified public holdings.'],
            ])
            @foreach ($services as [$icon, $title, $desc])
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
                    <div class="w-12 h-12 rounded-lg bg-gray-900 text-white flex items-center justify-center text-lg">
                        <i class="fas {{ $icon }}"></i>
                    </div>
                    <h5 class="mt-4 font-semibold text-gray-900">{!! $title !!}</h5>
                    <p class="mt-2 text-sm text-gray-600">{!! $desc !!}</p>
                </div>
            @endforeach
        </div>

        <div class="text-center mt-12">
            <a href="{{ route('invest.index') }}" class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-lg transition-colors">View Investment Plans</a>
        </div>
    </div>
</section>
@endsection
