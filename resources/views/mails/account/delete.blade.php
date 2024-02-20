@component('mail::message')

# Dear {{$notifiable->username}}.

We are sorry to see you leave, but we want to confirm that your {{config('app.name')}} account has been successfully deleted.

If you have any questions or need further assistance, please feel free to reach out.

Thank you for your time with us.

Best regards,

Regards, {{ config('app.name') }}

@endcomponent

