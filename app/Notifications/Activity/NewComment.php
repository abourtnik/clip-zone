<?php

namespace App\Notifications\Activity;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Notifications\Notification;

class NewComment extends Notification
{
    private Comment $comment;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
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
            'message' => 'You have new comment on your video !',
            'url' =>  $this->comment->video->route,
            'created_at' => now(),
            'is_read' => false
        ];
    }
}
