@component('mail::layout')
{{-- Header --}}
@slot('header')
@component('mail::header', ['url' => config('app.url')])
<img src="{{ asset('storage/app/public/'. $settings->logo) }}" alt="{{ $settings->site_name }}" style="height: 72px; width: auto;">
<div style="color:#FFFFFF; font-size:12px; font-weight:600; letter-spacing:1px; margin-top:8px; text-transform:uppercase;">Republic of Botswana</div>
@endcomponent
@endslot

{{-- Body --}}
{{ $slot }}

{{-- Subcopy --}}
@isset($subcopy)
@slot('subcopy')
@component('mail::subcopy')
{{ $subcopy }}
@endcomponent
@endslot
@endisset

{{-- Footer --}}
@slot('footer')
@component('mail::footer')
**{{ $settings->site_name }}**<br>
Republic of Botswana Investment Platform<br>
@isset($settings->contact_email)
{{ $settings->contact_email }}<br>
@endisset
© {{ date('Y') }} {{ $settings->site_name }}. @lang('All rights reserved.')<br>
This is an official, automated message. Investments are subject to applicable terms and conditions.
@endcomponent
@endslot
@endcomponent
