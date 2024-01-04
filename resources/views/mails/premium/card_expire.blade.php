@component('mail::message')

# Dear {{$notifiable->username}} !

This is a friendly reminder that your card ending in {{$notifiable->premium_subscription->card_last4}} expires in **{{$notifiable->premium_subscription->card_expired_at->diffInDays()}} days**.

To keep your Premium access and not risk blocking your services, please enter the information on your new card by clicking on the button above.

@component('mail::button', ['url' => $notifiable->billingPortalUrl(route('user.edit'))])
    Update now
@endcomponent

@component('mail::subcopy')
    Need help ? Contact our <a href="{{route('contact.show')}}">support</a>. Please don't reply to this email.
@endcomponent

Regards, {{ config('app.name') }}

@endcomponent
