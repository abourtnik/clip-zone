@component('mail::message')

# Hello {{$notifiable->username}} !

Thank you for subscription to {{config('app.name')}} Premium.

You will now be able to access to all premium features.

@component('mail::button', ['url' => route('pages.home')])
    Discover {{config('app.name')}} Premium
@endcomponent

Regards, {{ config('app.name') }}

@endcomponent
