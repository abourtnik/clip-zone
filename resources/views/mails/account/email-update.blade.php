@component('mail::message')

# {{ __('Hello :name !', ['name' => $notifiable->username]) }}

{{ __('A email update address has been requested for your :app account.', ['app' => config('app.name')]) }}

{{ __('Please confirm your change request by clicking the following link.') }}

@component('mail::button', ['url' => $url])
    {{ __('Confirm this email address') }}
@endcomponent

@component('mail::panel')
    {{ __('This link is available for :count minutes.', ['count' => config('auth.verification.expire')]) }}
@endcomponent

{{ __('If you did not initiate this request, you can ignore this email.') }}

{{ __('Regards,') }} {{ config('app.name') }}

@endcomponent
