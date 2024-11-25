<?php

namespace App\Notifications\Video;

use App\Models\User;
use App\Models\Video;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;

class VideoBanned extends Notification
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
        return ['mail', 'database', 'broadcast'];
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
            ->subject(Lang::get('Your video was suspended'))
            ->markdown('mails.video.ban', [
                'notifiable' => $notifiable,
                'video' => $this->video->loadCount('views'),
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  User $notifiable
     * @return array
     */
    public function toArray(User $notifiable) : array
    {
        return [
            'message' => 'You video ' .$this->video->title. ' was banned',
            'url' => route('user.videos.index'),
            'created_at' => now(),
            'is_read' => false
        ];
    }
}
