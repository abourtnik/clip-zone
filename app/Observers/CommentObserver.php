<?php

namespace App\Observers;

use App\Models\Activity;
use App\Models\Comment;
use App\Notifications\UserNotification;

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

        if ($comment->user->isNot($comment->video->user)) {

            $comment->video->user->notify(new UserNotification(
                'You have new comment on your video !',
                route('video.show', $comment->video)
            ));
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
        // Remove interaction for this comment
        $comment->interactions()->delete();

        // Remove replies and replies interactions
        $comment->replies_interactions()->delete();
        $comment->replies()->delete();

        // Remove video reports
        $comment->reports()->delete();

        // Remove activity for this comment
        Activity::forSubject($comment)->delete();
    }
}
