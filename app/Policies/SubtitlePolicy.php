<?php

namespace App\Policies;

use App\Models\Subtitle;
use App\Models\User;
use App\Models\Video;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class SubtitlePolicy
{
    use HandlesAuthorization;

    public const array ADMIN_ABILITIES = ['viewAny'];

    public function before(?User $user, string $ability): bool|null
    {
        if (in_array($ability, self::ADMIN_ABILITIES) && $user?->is_admin) {
            return true;
        }

        return null;
    }

    /**
     * Determine whether the user can view any subtitles.
     *
     * @param User $user
     * @param Video $video
     * @return Response|bool
     */
    public function viewAny(User $user, Video $video) : Response|bool
    {
        return $video->user()->is($user)
            ? Response::allow()
            : Response::denyWithStatus(403);
    }

    /**
     * Determine whether the user can create the subtitles.
     *
     * @param User $user
     * @param Video $video
     * @return Response|bool
     */
    public function create (User $user, Video $video): Response|bool
    {
        return $video->is_uploaded && !$video->is_banned && $video->user()->is($user)
            ? Response::allow()
            : Response::denyWithStatus(403);
    }

    public function show (?User $user, Subtitle $subtitle): Response|bool
    {
        return $subtitle->is_public || $subtitle->video->user->is($user)
            ? Response::allow()
            : Response::denyWithStatus(403);

    }

    /**
     * Determine whether the user can update the subtitle.
     *
     * @param  User $user
     * @param  Subtitle $subtitle
     * @return Response|bool
     */
    public function update(User $user, Subtitle $subtitle) : Response|bool
    {
        return $subtitle->video->user->is($user) && !$subtitle->video->is_banned && $subtitle->is_uploaded
            ? Response::allow()
            : Response::denyWithStatus(403);
    }

    /**
     * Determine whether the user can toggle the subtitle.
     *
     * @param  User $user
     * @param  Subtitle $subtitle
     * @return Response|bool
     */
    public function toggle(User $user, Subtitle $subtitle) : Response|bool
    {
        return $subtitle->video->user->is($user) && !$subtitle->video->is_banned && $subtitle->is_available
            ? Response::allow()
            : Response::denyWithStatus(403);
    }

    /**
     * Determine whether the user can delete the subtitle.
     *
     * @param  User $user
     * @param  Subtitle $subtitle
     * @return Response|bool
     */
    public function delete(User $user, Subtitle $subtitle) : Response|bool
    {
        return $subtitle->video->user->is($user) && !$subtitle->video->is_banned && $subtitle->is_uploaded
            ? Response::allow()
            : Response::denyWithStatus(404);
    }

}
