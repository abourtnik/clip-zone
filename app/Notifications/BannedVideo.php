<?php

namespace App\Notifications;

use App\Models\User;
use App\Models\Video;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;

class BannedVideo extends Notification
{

    private Video $video;

    public function __construct(Video $video)
    {
        $this->video = $video;
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
            ->subject(Lang::get('Your account was suspended'))
            ->markdown('mails.ban.video', [
                'notifiable' => $notifiable,
                'video' => $this->video->loadCount('views'),
            ]);
    }
}
