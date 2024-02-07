@component('mail::message')

# Dear {{$notifiable->username}},

Your **{{config('app.name')}} Premium** subscription has been canceled. We regret that you no longer wish to use this service. For information, you will no longer benefit from the benefits of your {{config('app.name')}} Premium subscription.

If you change your mind, you can easily renew your subscription.

@component('mail::button', ['url' => route('user.edit')])
    Renew my subscription
@endcomponent

Regards, {{ config('app.name') }}

@endcomponent
