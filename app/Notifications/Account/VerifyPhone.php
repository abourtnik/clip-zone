<?php

namespace App\Notifications\Account;

use App\Channels\SmsChannel;
use App\Channels\SmsMessage;
use App\Models\User;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Bus\Queueable;

class VerifyPhone extends Notification implements ShouldQueue
{
    use Queueable;
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
            ->subject(__('Phone number verification'))
            ->greeting(__('Hello :name !', ['name' => $notifiable->username]))
            ->line(__('Enter this code :code to validate your phone number :phone.', [
                'code' => $notifiable->getPhoneCodeVerification(),
                'phone' => $notifiable->getPhone(),
            ]));
    }

    /**
     * Get the sms representation of the notification.
     */
    public function toSms(User $notifiable): SmsMessage
    {
        return (new SmsMessage)
            ->line(__('Enter this code :code to validate your phone number.', [
                'code' => $notifiable->getPhoneCodeVerification(),
            ]));
    }
}
