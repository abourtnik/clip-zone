<?php

namespace App\Policies;

use Illuminate\Support\Number;
use App\Models\User;
use App\Models\Video;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Auth;

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
        return ($video->user()->is($user) && $video->is_created) || $user->is_admin
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
        if ($user?->is_admin) {
            return Response::allow();
        }

        return $video->is_public || $video->user()->is($user)
            ? Response::allow()
            : Response::denyWithStatus(404, $video->is_banned ? 'This video was banned' : 'This video is private');
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
        return $video->is_uploaded && (($video->is_public && $user->is_premium) || $video->user()->is($user))
            ? Response::allow()
            : Response::denyWithStatus(403, 'You are not authorized to download this video');
    }

    /**
     * Determine whether the user can see video file.
     *
     * @param User|null $user
     * @param Video $video
     * @return Response|bool
     */
    public function file(?User $user, Video $video): Response|bool
    {
        if ($user?->is_admin) {
            return Response::allow();
        }

        return $video->is_public || $video->user()->is($user)
            ? Response::allow()
            : Response::denyWithStatus(403, 'This video is private');
    }

    /**
     * Determine whether the user can see video active thumbnail.
     *
     * @param User|null $user
     * @param Video $video
     * @return Response|bool
     */
    public function thumbnail(?User $user, Video $video): Response|bool
    {
        return $video->is_public || $video->user->is($user)
            ? Response::allow()
            : Response::denyWithStatus(403, 'This video is private');
    }

    /**
     * Determine whether the user can see video thumbnails.
     *
     * @param User $user
     * @param Video $video
     * @return Response|bool
     */
    public function thumbnails(User $user, Video $video): Response|bool
    {
        return $video->user->is($user)
            ? Response::allow()
            : Response::denyWithStatus(404);
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
        return $video->user()->is($user) && !$video->is_banned && !$video->is_failed
            ? Response::allow()
            : Response::denyWithStatus(403, 'You are not authorized to edit this video');
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
        return $video->user()->isNot($user) && $video->is_public && !$video->isReportedByUser($user)
            ? Response::allow()
            : Response::denyWithStatus(403, 'You are not authorized to report this video');
    }

    /**
     * Determine whether the user can upload video.
     *
     * @param  User $user
     * @return Response|bool
     */
    public function upload(User $user) : Response|bool
    {
        if ($user->is_premium) {
            return Response::allow();
        }

        if ($user->uploaded_videos >= config('plans.free.max_uploads')) {
            return Response::denyWithStatus(403, 'Sorry ! You are limited to upload maximum ' .config('plans.free.max_uploads'). ' videos with free plan. Upgrade to Premium to upload more videos.');
        }

        $user_space = Auth::user()->uploaded_videos_size;

        if ($user_space + request()->get('resumableTotalSize') > config('plans.free.max_videos_storage')) {
            return Response::denyWithStatus(
                403,
                'Sorry ! Your space is limited to '.Number::fileSize(config('plans.free.max_videos_storage')).' with free plan. (Available space : '. Number::fileSize(config('plans.'.Auth::user()->plan.'.max_videos_storage') - $user_space) .'). Upgrade to Premium plan to increase your space storage.'
            );
        }

        return Response::allow();
    }

    /**
     * Determine whether the user can pin video.
     *
     * @param User $user
     * @param Video $video
     * @return Response|bool
     */
    public function pin(User $user, Video $video): Response|bool
    {
        return $video->user->is($user) && $video->is_active && !$video->is_pinned
            ? Response::allow()
            : Response::denyWithStatus(403);
    }

    /**
     * Determine whether the user can unpin video.
     *
     * @param User $user
     * @param Video $video
     * @return Response|bool
     */
    public function unpin(User $user, Video $video): Response|bool
    {
        return $video->user->is($user) && $video->is_active && $video->is_pinned
            ? Response::allow()
            : Response::denyWithStatus(403);
    }

    /**
     * Determine whether the user can like/dislike video.
     *
     * @param User $user
     * @param Video $video
     * @return Response|bool
     */
    public function interact(User $user, Video $video): Response|bool
    {
        return $video->is_active || $video->user->is($user)
            ? Response::allow()
            : Response::denyWithStatus(403, 'You are not authorized to interact with this video');
    }
}
