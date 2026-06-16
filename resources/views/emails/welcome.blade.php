{{-- blade-formatter-disable --}}
@component('mail::message')
# Welcome to {{$settings->site_name}}, {{$user->name}}

## The Republic of Botswana Investment Platform

Dear {{$user->name}},

We are pleased to confirm that your account on the **{{$settings->site_name}}** has been established. This platform is the official channel of the Republic of Botswana for managing your investment in a secure and transparent manner.

<hr style="border:none; border-top:1px solid #111111; margin:24px 0;">

### Getting Started

@component('mail::panel')
**To begin, please complete the following steps:**

1. **Verify your profile** — confirm your account details for added security.
2. **Review the available investment options** — consider the assets suited to your goals.
3. **Fund your account** — deposit an amount you are comfortable committing.
4. **Confirm your selection** — choose the asset that fits your objectives.
5. **Monitor your investment** — track your holdings from your account dashboard.
@endcomponent

@component('mail::button', ['url' => config('app.url').'/dashboard'])
Access Your Dashboard
@endcomponent

### Security and Stewardship

Your investment is administered with care. Please note:

- Your account is protected by standard encryption and access controls.
- The platform operates in line with applicable regulatory requirements.
- Account activity is monitored to help safeguard your investment.

### Support

Should you require assistance, our support team is available to help you.

@component('mail::button', ['url' => config('app.url').'/support', 'color' => 'success'])
Contact Support
@endcomponent

<hr style="border:none; border-top:1px solid #111111; margin:24px 0;">

We look forward to supporting your investment with the Republic of Botswana Investment Platform.

**The {{$settings->site_name}} Team**

@component('mail::subcopy')
**Disclosure:** All investments carry risk, and past performance does not guarantee future results. Please ensure you understand the terms applicable to your investment before proceeding. {{$settings->site_name}} is committed to responsible and transparent administration of your investment.
@endcomponent

@endcomponent
{{-- blade-formatter-disable --}}
