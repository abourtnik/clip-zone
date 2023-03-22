<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Video;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class VideoPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  User  $user
     * @return Response|bool
     */
    public function viewAny(User $user) : Response|bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  User $user
     * @param  Video $video
     * @return Response|bool
     */
    public function view(User $user, Video $video): Response|bool
    {
        return $video->user()->is($user) || $user->is_admin
            ? Response::allow()
            : Response::denyWithStatus(404);
    }

    /**
     * Determine whether the user can view video.
     *
     * @param User|null $user
     * @param Video $video
     * @return Response|bool
     */
    public function show(?User $user, Video $video): Response|bool
    {
        return $video->is_active || $video->user()->is($user)
            ? Response::allow()
            : Response::denyWithStatus(404, 'This video is private');
    }

    /**
     * Determine whether the user can download video.
     *
     * @param User $user
     * @param Video $video
     * @return Response|bool
     */
    public function download(User $user, Video $video): Response|bool
    {
        return $video->user()->is($user)
            ? Response::allow()
            : Response::denyWithStatus(403, 'You can only download your videos');
    }

    /**
     * Determine whether the user can create the model.
     *
     * @param  User $user
     * @return Response|bool
     */
    public function create(User $user) : Response|bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  User $user
     * @param  Video $video
     * @return Response|bool
     */
    public function update(User $user, Video $video) : Response|bool
    {
        return $video->user()->is($user)
            ? Response::allow()
            : Response::denyWithStatus(404);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  User $user
     * @param  Video $video
     * @return Response|bool
     */
    public function delete(User $user, Video $video) : Response|bool
    {
        return $video->user()->is($user)
            ? Response::allow()
            : Response::denyWithStatus(404);
    }

    /**
     * Determine whether the user can report video.
     *
     * @param  User $user
     * @param  Video $video
     * @return Response|bool
     */
    public function report(User $user, Video $video) : Response|bool
    {
        return $video->user()->isNot($user)
            ? Response::allow()
            : Response::denyWithStatus(403);
    }
}
