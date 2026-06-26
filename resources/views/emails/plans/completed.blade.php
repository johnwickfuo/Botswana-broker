@component('mail::message')
# Investment Matured

Dear {{ $name }},

Your investment in the **{{ $planName }}** plan on the United Arab Emirates Investment Platform has been completed.

## Investment Details
- **Investment Amount:** {{ $currency }}{{ number_format($amount, 2) }}
- **Total Profit Earned:** {{ $currency }}{{ number_format($profit, 2) }}
- **Total Return:** {{ $currency }}{{ number_format($totalReturn, 2) }}
- **Start Date:** {{ $startDate }}
- **End Date:** {{ $endDate }}

@if($profit > 0)
The returns from this investment have been credited to your account balance.
@else
Your investment has been completed. Please review your account for the latest balance.
@endif

<hr style="border:none; border-top:1px solid #111111; margin:24px 0;">

You may invest your funds in another asset or request a withdrawal from your account dashboard.

@component('mail::button', ['url' => $siteUrl . '/login'])
Access Your Account
@endcomponent

Thank you for entrusting {{ $siteName }} with your investment.

Regards,<br>
**The {{ $siteName }} Team**<br>
United Arab Emirates Investment Platform
@endcomponent
