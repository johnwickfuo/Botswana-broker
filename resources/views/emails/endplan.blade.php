{{-- blade-formatter-disable --}}
@component('mail::message')
# Hello {{ $demo->receiver_name }},

This is to notify you that your investment in {{ $demo->receiver_plan }} has reached its maturity date on the United Arab Emirates Investment Platform, and the capital for this investment has been added to your account and is available for withdrawal. <br>

<hr style="border:none; border-top:1px solid #111111; margin:24px 0;">

<strong style="color:#111111;">Asset:</strong> {{ $demo->receiver_plan }} <br>

<strong style="color:#111111;">Amount:</strong> {{ $demo->received_amount }} <br>

<strong style="color:#111111;">Date:</strong> {{ $demo->date }} <br>

<hr style="border:none; border-top:1px solid #111111; margin:24px 0;">

Kind regards,<br>
{{ $demo->sender }}<br>
United Arab Emirates Investment Platform
@endcomponent
{{-- blade-formatter-disable --}}
