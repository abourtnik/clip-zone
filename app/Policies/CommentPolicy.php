<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Comment;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class CommentPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param Comment $comment
     * @return Response|bool
     */
    public function viewAny(Comment $comment) : Response|bool
    {
        return true;
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
     * @param  User $user
     * @return Response|bool
     */
    public function create(User $user)
    {
        return true;
    }



    /**
     * Determine whether the user can update the model.
     *
     * @param  User $user
     * @param  Comment $comment
     * @return Response|bool
     */
    public function update(User $user, Comment $comment)
    {
        return $comment->user->is($user);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  User $user
     * @param  Comment $comment
     * @return Response|bool
     */
    public function delete(User $user, Comment $comment)
    {
        return $comment->user->is($user);
    }
}
