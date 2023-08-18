@component('mail::message')

# Dear {{$notifiable->username}} !

Please see attached invoice for your Premium subscription on {{config('app.name')}} for **{{$transaction->date->format('d F Y')}} to {{$transaction->subscription->next_payment->format('d F Y')}}**

@component('mail::panel')
    Your next debit will be on {{$transaction->subscription->next_payment->format('d F Y')}}
@endcomponent

Regards, {{ config('app.name') }}

@endcomponent
