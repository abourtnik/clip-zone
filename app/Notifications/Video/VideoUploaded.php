<?php

namespace App\Notifications\Video;

use App\Models\User;
use App\Models\Video;
use Illuminate\Notifications\Notification;

class VideoUploaded extends Notification
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
            'message' => 'Your video : ' .$this->video->title. ' was successfully processed.',
            'url' => $this->video->is_draft ? route('user.videos.create', $this->video) : route('user.videos.edit', $this->video),
            'created_at' => now()->diffForHumans()
        ];
    }
}
