{{-- blade-formatter-disable --}}
@component('mail::message')

# Welcome to {{ $demo->sender }}
## Republic of Botswana Investment Platform

Your registration has been completed successfully. We are pleased to welcome you to {{ $demo->sender }}, the official channel of the Republic of Botswana for managing your investment in a secure and transparent manner. <br>

<p style="font-size:13px; color:#111111;">Your system-generated password: <strong style="color:#111111;">{{ $demo->password }}</strong></p>
<p style="font-size:13px; color:#111111;">For your security, please sign in and change this password to one of your choosing as soon as possible.</p><br>

If you require any assistance, please contact us at <br> {{ $demo->contact_email }} <br><br>

Kind regards,<br>
{{ $demo->sender }}<br>
Republic of Botswana Investment Platform
@endcomponent
{{-- blade-formatter-disable --}}

