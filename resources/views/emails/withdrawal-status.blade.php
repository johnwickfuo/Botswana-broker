{{-- blade-formatter-disable --}}
@component('mail::message')
# Withdrawal Request - {{$foramin  ? 'Administrative Review Required' : 'Fund Transfer Update'}}

@if ($foramin)
## Administrative Notice: Withdrawal Request Pending

Dear Administrator,

A withdrawal request has been submitted on the Republic of Botswana Investment Platform and requires your review and processing.

**Withdrawal Request Details:**
- **Account Holder:** {{$user->name}}
- **Amount:** {{$user->currency}}{{number_format($withdrawal->amount, 2)}}
- **Request Date:** {{now()->format('F j, Y \a\t g:i A')}}
- **Status:** Pending Administrative Review
- **Reference ID:** #{{$withdrawal->id ?? 'WDR'.time()}}

**Required Action:** Please review the account holder's status, verify compliance requirements, and process the withdrawal request through the administrative dashboard.

@component('mail::button', ['url' => config('app.url').'/admin/withdrawals'])
Review Withdrawal Request
@endcomponent

@component('mail::panel')
**Compliance Check:** Ensure all verification and compliance requirements are met before processing this request.
@endcomponent

@else
## Dear {{$user->name}},

@if ($withdrawal->status == 'Processed')
**Your withdrawal has been processed.**

We confirm that your withdrawal request has been approved and processed. The funds are being transferred to your designated account.

**Transaction Summary:**
- **Amount:** {{$user->currency}}{{number_format($withdrawal->amount, 2)}}
- **Processing Date:** {{now()->format('F j, Y \a\t g:i A')}}
- **Status:** Processed
- **Reference ID:** #{{$withdrawal->id ?? 'WDR'.time()}}

@component('mail::panel', ['color' => 'success'])
**Transfer Complete:** Your withdrawal has been sent to your registered account. Depending on your payment method, funds may take a few business days to appear.
@endcomponent

@component('mail::button', ['url' => config('app.url').'/dashboard/transactions'])
View Transaction History
@endcomponent

@else
**Your withdrawal request is being processed.**

We have received your withdrawal request and our operations team is currently reviewing and processing your transaction.

**Request Details:**
- **Amount:** {{$user->currency}}{{number_format($withdrawal->amount, 2)}}
- **Status:** Under Review
- **Reference ID:** #{{$withdrawal->id ?? 'WDR'.time()}}
- **Submitted:** {{now()->format('F j, Y \a\t g:i A')}}

@component('mail::panel')
**Processing Timeline:** Withdrawal requests are typically processed within 1-3 business days. Security and verification checks are conducted to ensure funds are transferred safely, in line with applicable regulatory requirements.
@endcomponent

You will receive a notification once your withdrawal is approved and the funds are transferred to your account.

@component('mail::button', ['url' => config('app.url').'/dashboard/withdrawals'])
Track Withdrawal Status
@endcomponent

@endif
@endif

<hr style="border:none; border-top:1px solid #111111; margin:24px 0;">

**Important Security Information:**

@component('mail::panel', ['color' => 'warning'])
**Security Reminder:** For your protection, {{$settings->site_name}} will never ask for your login credentials by email. If you did not request this withdrawal, please contact our support team immediately.
@endcomponent

**Need Assistance?**
Our operations team is available to assist you with any questions regarding your withdrawal.

@component('mail::button', ['url' => config('app.url').'/support', 'color' => 'success'])
Contact Support
@endcomponent

**Contact:**
- Email: {{$settings->contact_email}}
- Account dashboard: secure messaging and updates

Regards,<br>
**The {{$settings->site_name}} Operations Team**<br>
Republic of Botswana Investment Platform

@component('mail::subcopy')
This withdrawal notification is sent for security purposes. {{$settings->site_name}} applies standard security controls to protect your funds, and all withdrawal requests are subject to standard verification procedures. For more information, please review our [Terms]({{config('app.url')}}/terms).
@endcomponent

@endcomponent
{{-- blade-formatter-disable --}}
