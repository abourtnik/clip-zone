@component('mail::message')

# Hello {{$notifiable->username}} !

You are receiving this email because your password has been updated.

If you did not request a password update, please **immediately** contact our support.

@component('mail::button', ['url' => route('pages.home')])
Contact Support
@endcomponent

Regards, {{ config('app.name') }}

@endcomponent
