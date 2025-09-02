@component('mail::message')

# Dear {{$notifiable->username}}.

Your last payment of **{{ $amount }}** for {{ config('app.name') }} Premium was unsuccessful.

Please update your payment method to continue to profit premium features.

@component('mail::button', ['url' => $url])
    Update payment information
@endcomponent

Regards, {{ config('app.name') }}

@endcomponent
