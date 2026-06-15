{{-- blade-formatter-disable --}}
@component('mail::message')
# Investment Return Credited

## Dear {{$user->name}},

We are writing to confirm that a new return has been generated on your investment with the Republic of Botswana Investment Platform and credited to your account.

### Return Details

@component('mail::panel', ['color' => 'success'])
**Investment Summary**

**Investment Plan:** {{$plan}}<br>
**Return Amount:** {{$user->currency}}{{number_format($amount, 2)}}<br>
**Generated On:** {{$plandate}}<br>
**Status:** Credited to Your Account
@endcomponent

Your **{{$plan}}** investment plan continues to accrue returns as administered on the platform. These returns are credited to your account in accordance with the terms of your selected plan.

@component('mail::button', ['url' => config('app.url').'/dashboard'])
View Your Investment
@endcomponent

<hr style="border:none; border-top:1px solid #111111; margin:24px 0;">

### Managing Your Investment

- Monitor your holdings and returns from your account dashboard
- Review the investment plans available on the platform
- Keep your account details up to date for security

@component('mail::button', ['url' => config('app.url').'/login', 'color' => 'success'])
Access Your Account
@endcomponent

<hr style="border:none; border-top:1px solid #111111; margin:24px 0;">

### Transparency

We are committed to transparent administration of your investment. You may access your account activity, return history, and statements through your dashboard at any time.

Thank you for entrusting {{$settings->site_name}} with your investment.

**Regards,**<br>
**The {{$settings->site_name}} Team**<br>
Republic of Botswana Investment Platform

<hr style="border:none; border-top:1px solid #111111; margin:24px 0;">

@component('mail::subcopy')
**Disclosure:** All investments carry risk, and past performance does not guarantee future results. This notification is provided for information only and should not be considered financial advice. Returns are calculated based on the performance of your selected investment plan. {{$settings->site_name}} is committed to responsible and transparent administration of your investment.
@endcomponent

@endcomponent
{{-- blade-formatter-disable --}}
