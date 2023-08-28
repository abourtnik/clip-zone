@component('mail::message')

# Hello {{$notifiable->username}},

Thank you for subscription to **{{config('app.name')}} Premium** ! Your {{config('plans.trial_period.period')}} days trial begins immediately and will end on {{$notifiable->premium_subscription->trial_ends_at->format('M. d, Y')}}. Your payment method will be charged {{$notifiable->premium_subscription->plan->name}} beginning **{{$notifiable->premium_subscription->trial_ends_at->format('M. d, Y')}}**.

You can manage and cancel your subscription at any time by going to your {{config('app.name')}} <a class="text-decoration-none" href="{{route('user.edit')}}">account</a>.

@component('mail::subcopy')
You can cancel your {{config('app.name')}} Premium subscription at any time. If you cancel your subscription, you retain access to {{config('app.name')}} Premium until the end of the billing period. No partial refund will be given.

Need help ? Contact our <a href="{{route('contact.show')}}">support</a>. Please don't reply to this email.
@endcomponent

Regards, {{ config('app.name') }}

@endcomponent
