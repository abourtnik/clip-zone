<?php

namespace App\Channels;

use App\Integrations\BrevoClient;
use App\Models\User;
use Illuminate\Notifications\Notification;

class SmsChannel
{
    /**
     * Send the given notification.
     */
    public function send(User $notifiable, Notification $notification): void
    {
        /**
         * @var SmsMessage $sms
         */
        $sms = $notification->toSms($notifiable);

        $brevoClient = app(BrevoClient::class);

        $brevoClient->sendTransactionalSMS($notifiable->getPhone(), $sms->getLines());
    }
}
