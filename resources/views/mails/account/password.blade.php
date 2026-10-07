@component('mail::message')

# {{ __('Hello :name !', ['name' => $notifiable->username]) }}

{{ __('You are receiving this email because your password has been updated.') }}

{{ __('If you did not request a password update, please **immediately** contact our support.') }}

@component('mail::button', ['url' => route('contact.show')])
{{ __('Contact Support') }}
@endcomponent

{{ __('Regards,') }} {{ config('app.name') }}

@endcomponent
