<?php

namespace App\Notifications\Premium;

use App\Models\User;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;

class CardExpired extends Notification
{
    /**
     * Get the notification's channels.
     *
     * @param User $notifiable
     * @return array|string
     */
    public function via(User $notifiable): array|string
    {
        return ['mail'];
    }

    /**
     * Build the mail representation of the notification.
     *
     * @param User $notifiable
     * @return MailMessage
     */
    public function toMail(User $notifiable) : MailMessage
    {
        return $this->buildMailMessage($notifiable);
    }

    /**
     * Get the reset email notification mail message for the given URL.
     *
     * @param User $notifiable
     * @return MailMessage
     */
    protected function buildMailMessage(User $notifiable) : MailMessage
    {
        return (new MailMessage)
            ->subject(Lang::get('Your credit card is about to expire'))
            ->markdown('mails.premium.card_expire', [
                'notifiable' => $notifiable
            ]);
    }
}
