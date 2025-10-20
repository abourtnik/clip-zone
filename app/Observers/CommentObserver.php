<?php

namespace App\Observers;

use App\Models\Comment;
use App\Notifications\Activity\NewComment;

class CommentObserver
{
    /**
     * Handle the Comment "creating" event.
     *
     * @param Comment $comment
     * @return void
     */
    public function creating(Comment $comment) : void
    {
        $comment->ip = request()->getClientIp();
    }

    /**
     * Handle the Comment "creating" event.
     *
     * @param Comment $comment
     * @return void
     */
    public function created(Comment $comment) : void
    {
        if ($comment->user->isNot($comment->video->user)) {
            $comment->video->user->notify(new NewComment($comment));
        }
    }
}
