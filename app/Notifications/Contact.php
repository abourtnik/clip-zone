<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Config;
use Illuminate\Notifications\AnonymousNotifiable;


class Contact extends Notification
{
    public string $name;
    public string $email;
    public string $message;


    /**
     * Create a notification instance.
     *
     * @param string $name
     * @param string $email
     * @param string $message
     * @return void
     */
    public function __construct(string $name, string $email, string $message)
    {
        $this->name = $name;
        $this->email = $email;
        $this->message = $message;
    }
    /**
     * Get the notification's channels.
     *
     * @param AnonymousNotifiable $notifiable
     * @return array|string
     */
    public function via(AnonymousNotifiable $notifiable): array|string
    {
        return ['mail'];
    }

    /**
     * Build the mail representation of the notification.
     *
     * @param AnonymousNotifiable $notifiable
     * @return MailMessage
     */
    public function toMail(AnonymousNotifiable $notifiable): MailMessage
    {
        return $this->buildMailMessage($notifiable);
    }

    /**
     * Get the contact email notification mail message.
     *
     * @param AnonymousNotifiable $notifiable
     * @return MailMessage
     */
    protected function buildMailMessage(AnonymousNotifiable $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New message contact on ' .Config::get('app.name'). ' !')
            ->from($this->email, $this->name)
            ->replyTo($this->email, $this->name)
            ->markdown('mails.contact', [
                'name' => $this->name,
                'message' => $this->message
            ]);
    }
}
