@component('mail::message')

# {{ __('Dear :name', ['name' => $notifiable->username]) }}

{{ __('Please see attached invoice for your Premium subscription on :app account for **:from to :to**.', [
    'app'  => config('app.name'),
    'from' => $transaction->date->translatedFormat('d F Y'),
    'to'   => $transaction->subscription->next_payment->translatedFormat('d F Y'),
]) }}

@component('mail::panel')
    {{ __('Your next debit will be on :date', [
        'date' => $transaction->subscription->next_payment->translatedFormat('d F Y'),
    ]) }}
@endcomponent

{{ __('Regards,') }} {{ config('app.name') }}

@endcomponent
