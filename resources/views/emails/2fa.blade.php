{{-- blade-formatter-disable --}}
@component('mail::message')
# Two-Factor Authentication Code

A request to authenticate your account on the United Arab Emirates Investment Platform has been received.<br>
Please use the following one-time code to complete your sign-in:<br><br>

<div style="text-align:center; margin:16px 0;">
<span style="display:inline-block; font-size:24px; font-weight:700; letter-spacing:4px; color:#111111; border:1px solid #111111; padding:12px 24px;">{!! $demo->message !!}</span>
</div>

If you did not request this code, please disregard this message and secure your account.<br><br>

Regards,<br>
{{ $demo->sender }}.
@endcomponent
{{-- blade-formatter-disable --}}
