@component('mail::message')

# Hello {{$notifiable->username}} !

A email update address has been requested for your {{ config('app.name') }} account.

Please confirm your change request by clicking the following link.

@component('mail::button', ['url' => $url])
Confirm this email address
@endcomponent

@component('mail::panel')
    This link is available for {{ config('auth.verification.expire') }} minutes.
@endcomponent

If you did not initiate this request, you can ignore this email.

Regards, {{ config('app.name') }}

{{--@component('mail::subcopy')
    If you're having trouble clicking the "Verify Email Address" button, copy and paste the URL below
    into your web browser: <a href="{{$url}}">{{$url}}</a>
@endcomponent--}}
@endcomponent
