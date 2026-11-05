<?php

namespace App\Listeners;

use App\Events\Comment\CommentBanned;
use App\Notifications\Comment\CommentBanned as CommentBannedNotification;

class CommentEventSubscriber
{
    /**
     * Handle comment banned events.
     */
    public function handleCommentBanned(CommentBanned $event): void
    {
        $event->comment->user->notify(new CommentBannedNotification($event->comment));
    }
}
