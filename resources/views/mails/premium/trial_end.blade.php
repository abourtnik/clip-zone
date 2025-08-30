@component('mail::message')

# Dear {{$notifiable->username}} !

Your free trial with {{ config('app.name') }} started on {{$notifiable->premium_subscription->created_at->format('F j, Y')}} ending in **{{config('plans.trial_period.email_reminder')}} days**.

@component('mail::panel')
    Your default payment method will be charged **{{$notifiable->premium_subscription->plan->price}} every {{$notifiable->premium_subscription->plan->period}}**, unless you cancel.
@endcomponent

If you would like to cancel your subscription, please click on the followed link.

@component('mail::button', ['url' => route('user.edit')])
    Manage your subscription
@endcomponent

Regards, {{ config('app.name') }}

@endcomponent
