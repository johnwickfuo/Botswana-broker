{{-- blade-formatter-disable --}}
@component('mail::message')
# Deposit Confirmation - {{$foramin  ? 'Administrative Notification' : 'Investment Account Funded'}}

@if ($foramin)
## Administrative Notice: Deposit Received

Dear Administrator,

A new deposit has been received on the Republic of Botswana Investment Platform:

**Deposit Details:**
- **Account Holder:** {{$user->name}}
- **Amount:** {{$user->currency}}{{number_format($deposit->amount, 2)}}
- **Status:** {{$deposit->status}}
- **Date:** {{now()->format('F j, Y \a\t g:i A')}}

@if($deposit->status != "Processed")
**Action Required:** Please review and process this deposit through the administrative dashboard.

@component('mail::button', ['url' => config('app.url').'/admin/dashboard'])
Process Deposit
@endcomponent
@else
This deposit has been processed and the account holder's balance has been credited.
@endif

@else
## Dear {{$user->name}},

@if ($deposit->status == 'Processed')
**Your deposit has been received and processed.**

We confirm that your deposit of **{{$user->currency}}{{number_format($deposit->amount, 2)}}** has been received and credited to your investment account on the Republic of Botswana Investment Platform.

**Next Steps:**
- Your funds are now available within your account
- Review the available assets suited to your goals
- Invest your funds in an asset from your dashboard

@component('mail::button', ['url' => config('app.url').'/dashboard'])
Access Your Dashboard
@endcomponent

<hr style="border:none; border-top:1px solid #111111; margin:24px 0;">

**Managing Your Investment:**
- Review the assets available on the platform
- Monitor your holdings and account activity
- Track returns credited to your account over time

@else
**Your deposit is being processed.**

We have received your deposit of **{{$user->currency}}{{number_format($deposit->amount, 2)}}**. Your transaction is currently under review by our operations team.

**Processing Status:** Under Review
**Expected Processing Time:** 1-3 business hours

You will receive a notification once your deposit is confirmed and your investment account is credited.

@component('mail::panel')
**Security Notice:** Your funds are handled under strict security controls throughout the processing period, in line with applicable regulatory requirements.
@endcomponent

@endif
@endif

<hr style="border:none; border-top:1px solid #111111; margin:24px 0;">

**Need Assistance?**
Our support team is available to assist you with any questions regarding your account.

@component('mail::button', ['url' => config('app.url').'/support', 'color' => 'success'])
Contact Support
@endcomponent

Regards,<br>
**The {{$settings->site_name}} Team**<br>
Republic of Botswana Investment Platform

@component('mail::subcopy')
This is an automated message from {{$settings->site_name}}. For your security, please do not share this email with anyone. If you did not initiate this deposit, please contact our support team immediately. All investments carry risk; please ensure you understand the terms applicable to your investment.
@endcomponent

@endcomponent
{{-- blade-formatter-disable --}}
