<?php

namespace App\Notifications\Activity;

use App\Models\User;
use App\Models\Video;
use Illuminate\Notifications\Notification;

class NewVideo extends Notification
{
    private Video $video;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Video $video)
    {
        $this->video = $video;
    }


    /**
     * Get the notification's delivery channels.
     *
     * @param User $notifiable
     * @return array
     */
    public function via(User $notifiable) : array
    {
        return ['database', 'broadcast'];
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
            'message' => $this->video->user->username. ' has published new video !',
            'url' => $this->video->route,
            'created_at' => now(),
            'is_read' => false
        ];
    }
}
