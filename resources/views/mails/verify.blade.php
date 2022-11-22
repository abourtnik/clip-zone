@component('mail::message')

# Welcome on Youtube !

You have just created an account on <a href="{{ config('app.name') }}">{{ Str::after(config('app.url'), '://') }}</a>. To activate your account, simply click on this link :

@component('mail::button', ['url' => $url])
Verify Email Address
@endcomponent

@component('mail::panel')
    This link is available for {{ config('auth.verification.expire') }} minutes.
@endcomponent



If you did not create an account, no further action is required.

Regards, {{ config('app.name') }}

{{--@component('mail::subcopy')
    If you're having trouble clicking the "Verify Email Address" button, copy and paste the URL below
    into your web browser: <a href="{{$url}}">{{$url}}</a>
@endcomponent--}}
@endcomponent
