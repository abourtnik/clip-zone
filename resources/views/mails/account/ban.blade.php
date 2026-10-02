@component('mail::message')

# {{ __('Hello :name !', ['name' => $notifiable->username]) }}

{!! __('You are receiving this email because your account has been suspended for violating our :terms.', ['terms' => '<a target="_blank" class="text-danger fw-bold" href="'.e(url('/terms')).'">'.e(__('Terms of Service')).'</a>',]) !!}

{{ __('If you want contest this decision, please contact our support.') }}

@component('mail::button', ['url' => route('contact.show')])
    {{ __('Contact Support') }}
@endcomponent

{{ __('Regards,') }} {{ config('app.name') }}

@endcomponent

