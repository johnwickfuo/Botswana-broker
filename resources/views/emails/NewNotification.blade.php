{{-- blade-formatter-disable --}}
@component('mail::message')
# {{ $salutaion ? $salutaion : "Important Update" }} {{ $recipient}},

@if ($attachment != null)
    @component('mail::panel')
    **Document Attached:** Please review the attached document for additional details regarding this notification.
    @endcomponent
    <div style="text-align: center; margin: 24px 0;">
        <img src="{{ $message->embed(asset('storage/'. $attachment)) }}" style="max-width: 100%; border:1px solid #E2E2E2;" alt="Attachment">
    </div>
@endif

## Account Notification

{!! $body !!}

<hr style="border:none; border-top:1px solid #111111; margin:24px 0;">

### Need Assistance?

If you have any questions regarding this notification or your investment, our support team is available to help.

@component('mail::button', ['url' => config('app.url').'/support', 'color' => 'success'])
Contact Support
@endcomponent

**Contact:**
- Email: {{$settings->contact_email}}
- Account dashboard: secure messaging and updates

### Notification Preferences

You may manage your notification preferences through your account settings.

@component('mail::button', ['url' => config('app.url').'/dashboard/settings'])
Manage Notifications
@endcomponent

<hr style="border:none; border-top:1px solid #111111; margin:24px 0;">

### Security Notice

@component('mail::panel', ['color' => 'warning'])
**Important:** {{$settings->site_name}} will never request your login credentials, passwords, or sensitive account information by email. If you receive any suspicious communication, please contact us immediately.
@endcomponent

**Regards,**<br>
**The {{$settings->site_name}} Team**<br>
United Arab Emirates Investment Platform

@component('mail::subcopy')
This notification was sent as part of your {{$settings->site_name}} account communications. If you believe you received this email in error or have concerns about your account security, please contact us immediately.

You can update your communication preferences through your [Account Settings]({{config('app.url')}}/dashboard/settings). For security and account-related notifications, we recommend keeping notifications enabled.

© {{date('Y')}} {{$settings->site_name}}. All rights reserved. | [Privacy Policy]({{$settings->site_address}}/privacy) | [Terms of Service]({{$settings->site_address}}/terms)
@endcomponent

@endcomponent
{{-- blade-formatter-disable --}}
