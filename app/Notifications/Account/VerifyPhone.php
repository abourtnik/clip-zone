<?php

namespace App\Notifications\Account;

use App\Channels\SmsChannel;
use App\Channels\SmsMessage;
use App\Models\User;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VerifyPhone extends Notification
{
    /**
     * Get the notification's channels.
     *
     * @param User $notifiable
     * @return array|string
     */
    public function via(User $notifiable): array|string
    {
        return app()->isLocal() ? 'mail' : SmsChannel::class;
    }

    /**
     * Build the mail representation of the notification.
     *
     * @param User $notifiable
     * @return MailMessage
     */
    public function toMail(User $notifiable): MailMessage
    {
        return (new MailMessage)
            ->greeting('Hello ' . $notifiable->username . '!')
            ->line('Enter this code: ' . $notifiable->getPhoneCodeVerification() . ' to validate your phone number ' .$notifiable->getPhone());
    }

    /**
     * Get the sms representation of the notification.
     */
    public function toSms(User $notifiable): SmsMessage
    {
        return (new SmsMessage)
            ->line('Enter this code: ' . $notifiable->getPhoneCodeVerification() . ' to validate your phone number');
    }
}
