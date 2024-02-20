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

    /**
     * Handle the Comment "deleting" event.
     *
     * @param Comment $comment
     * @return void
     */
    public function deleting(Comment $comment) : void
    {
        // Remove interaction for this comment with call interaction deleting observer
        $comment->interactions()->each(fn ($interaction) => $interaction->delete());

        // Remove replies and replies interactions with call interaction deleting observer
        $comment->replies_interactions()->each(fn ($interaction) => $interaction->delete());
        $comment->replies()->delete();

        // Remove video reports
        $comment->reports()->delete();
    }
}
