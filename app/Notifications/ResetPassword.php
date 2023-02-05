<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\URL;

class ResetPassword extends Notification
{
    /**
     * The password reset token.
     *
     * @var string
     */
    public string $token;


    /**
     * Create a notification instance.
     *
     * @param string $token
     * @return void
     */
    public function __construct(string $token)
    {
        $this->token = $token;
    }

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
        $resetUrl = $this->resetUrl($notifiable);

        return $this->buildMailMessage($resetUrl, $notifiable);
    }

    /*
     * Get the reset email notification mail message for the given URL.
     *
     * @param  string $url
     * @return MailMessage
     */
    protected function buildMailMessage(string $url, User $notifiable) : MailMessage
    {
        return (new MailMessage)
            ->subject(Lang::get('Reset Password Notification'))
            ->markdown('mails.forgot', compact('url', 'notifiable'));
    }

    /**
     * Get the verification URL for the given notifiable.
     *
     * @param User $notifiable
     * @return string
     */
    protected function resetUrl(User $notifiable) : string
    {
        return URL::route(
            'password.reset',
            [
                'id' => $notifiable->getKey(),
                'token' => $this->token,
            ]
        );
    }
}
