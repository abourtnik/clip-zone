<?php

namespace App\Notifications\Video;

use App\Models\User;
use App\Models\Video;
use Illuminate\Notifications\Notification;

class VideoError extends Notification
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
            'message' => 'The processing of your video : ' .$this->video->title. ' failed !',
            'url' => route('user.videos.index'),
            'created_at' => now(),
            'is_read' => false
        ];
    }
}
