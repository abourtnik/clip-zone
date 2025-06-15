<?php

namespace App\Channels;

use App\Models\User;
use App\Services\BrevoService;
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

        $brevoService = app(BrevoService::class);

        $brevoService->sendTransactionalSMS($notifiable->getPhone(), $sms->getLines());
    }
}
