<?php

namespace App\Observers;

use App\Models\Activity;
use App\Models\Comment;

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
     * Handle the Comment "deleting" event.
     *
     * @param Comment $comment
     * @return void
     */
    public function deleting(Comment $comment) : void
    {
        // Remove interaction for this comment
        $comment->interactions()->delete();

        // Remove replies and replies interactions
        $comment->replies_interactions()->delete();
        $comment->replies()->delete();

        // Remove activity for this comment
        Activity::forSubject($comment)->delete();
    }
}
