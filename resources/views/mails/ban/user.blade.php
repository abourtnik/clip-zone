@component('mail::message')

# Hello {{$notifiable->username}}.

You are receiving this email because your account has been suspended for violating our <a target="_blank" class="text-danger fw-bold" href="/terms">Terms of Service</a>.

If you want contest this decision, please contact our support.

@component('mail::button', ['url' => route('contact.show')])
    Contact Support
@endcomponent

Regards, {{ config('app.name') }}

@endcomponent

