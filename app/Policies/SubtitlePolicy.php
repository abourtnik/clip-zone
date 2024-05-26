<?php

namespace App\Policies;

use App\Models\Subtitle;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class SubtitlePolicy
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
        return request()->route('video')->user->is($user)
            ? Response::allow()
            : Response::denyWithStatus(404);
    }

    public function create (User $user): Response|bool
    {
        return request()->route('video')->user->is($user)
            ? Response::allow()
            : Response::denyWithStatus(404);
    }

    public function show (?User $user, Subtitle $subtitle): Response|bool
    {
        return $subtitle->is_public || $subtitle->video->user->is($user)
            ? Response::allow()
            : Response::denyWithStatus(404);

    }

    public function update(User $user, Subtitle $subtitle) : Response|bool
    {
        return $subtitle->video->user->is($user)
            ? Response::allow()
            : Response::denyWithStatus(404);
    }

    public function delete(User $user, Subtitle $subtitle) : Response|bool
    {
        return $subtitle->video->user->is($user)
            ? Response::allow()
            : Response::denyWithStatus(404);
    }

}
