<?php

namespace App\Notifications\Account;

use App\Models\User;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;

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
        return ['sms'];
    }

    /**
     * Build the mail representation of the notification.
     *
     * @param User $notifiable
     * @return MailMessage
     */
    public function toMail(User $notifiable): MailMessage
    {
        $verificationUrl = $this->verificationUrl($notifiable);

        return $this->buildMailMessage($verificationUrl, $notifiable);
    }

    /**
     * Get the verify email notification mail message for the given URL.
     *
     * @param string $url
     * @param User $notifiable
     * @return MailMessage
     */
    protected function buildMailMessage(string $url, User $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Welcome on ' .Config::get('app.name'). ' !')
            ->markdown('mails.account.verify', compact('url', 'notifiable'));
    }

    /**
     * Get the verification URL for the given notifiable.
     *
     * @param User $notifiable
     * @return string
     */
    protected function verificationUrl(User $notifiable) : string
    {
        return URL::route(
            'registration.confirm',
            [
                'id' => $notifiable->getKey(),
                'token' => $notifiable->confirmation_token,
            ]
        );
    }
}
