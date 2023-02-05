@component('mail::message')

# Hello {{$notifiable->username}} !

You are receiving this email because we received a password reset request for your account.

@component('mail::button', ['url' => $url])
Reset Password
@endcomponent

@component('mail::panel')
    This password reset link will expire in {{ config('auth.passwords.'.config('auth.defaults.passwords').'.expire') }} minutes.
@endcomponent

If you did not request a password reset, no further action is required.

Regards, {{ config('app.name') }}

@endcomponent
