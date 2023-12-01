<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Comment;
use App\Models\Video;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class CommentPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param User $user
     * @return Response|bool
     */
    public function viewAny(User $user) : Response|bool
    {
        return true;
    }

    public function list(?User $user, Video $video) : Response|bool
    {
        return ($video->is_public && $video->allow_comments) || $video->user()->is($user)
            ? Response::allow()
            : Response::denyWithStatus(404, !$video->is_public ? 'This video is private' : 'Comments are turned off');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @param Comment $comment
     * @return Response|bool
     */
    public function view(User $user, Comment $comment): Response|bool
    {
        return true;
    }

    /**
     * Determine whether the user can view Comment.
     *
     * @param User|null $user
     * @param Comment $comment
     * @return Response|bool
     */
    public function show(?User $user, Comment $comment): Response|bool
    {
        return true;
    }

    /**
     * Determine whether the user can create the model.
     *
     * @param User $user
     * @param Video $video
     * @return Response|bool
     */
    public function create(User $user, Video $video): Response|bool
    {
        return ($video->is_public && $video->allow_comments) || $video->user()->is($user)
            ? Response::allow()
            : Response::denyWithStatus(404, !$video->is_public ? 'This video is private' : 'Comments are turned off');
    }



    /**
     * Determine whether the user can update the model.
     *
     * @param  User $user
     * @param  Comment $comment
     * @return Response|bool
     */
    public function update(User $user, Comment $comment) : Response|bool
    {
        return ($comment->user->is($user) && $comment->video->is_public) || ($comment->video->user()->is($user) && $comment->user->is($user))
            ? Response::allow()
            : Response::denyWithStatus(404, 'This video is private');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  User $user
     * @param  Comment $comment
     * @return Response|bool
     */
    public function delete(User $user, Comment $comment): Response|bool
    {
        return ($comment->user->is($user) && $comment->video->is_public) || $comment->video->user()->is($user)
            ? Response::allow()
            : Response::denyWithStatus(403);
    }

    /**
     * Determine whether the user can report the model.
     *
     * @param  User $user
     * @param  Comment $comment
     * @return Response|bool
     */
    public function report(User $user, Comment $comment): Response|bool
    {
        return $comment->video->is_public && $comment->user->isNot($user) && !$comment->is_banned && !$comment->isReportedByUser($user)
            ? Response::allow()
            : Response::denyWithStatus(403, 'You are not authorized to report this comment');
    }

    /**
     * Determine whether the user can pin the model.
     *
     * @param  User $user
     * @param  Comment $comment
     * @return Response|bool
     */
    public function pin(User $user, Comment $comment): Response|bool
    {
        return $comment->video->user->is($user) && !$comment->is_reply
            ? Response::allow()
            : Response::denyWithStatus(403);
    }
}
