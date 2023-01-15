<?php

namespace App\Observers;

use App\Models\Comment;

class CommentObserver
{
    /**
     * Handle the Comment "creating" event.
     *
     * @param Comment $category
     * @return void
     */
    public function creating(Comment $comment)
    {
        $comment->ip = request()->getClientIp();
    }
}
