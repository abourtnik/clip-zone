<?php

namespace App\Policies;

use App\Models\Subtitle;
use App\Models\User;
use App\Models\Video;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;
use Illuminate\Http\Request;

class SubtitlePolicy
{
    use HandlesAuthorization;

    private Video $video;

    public function __construct(Request $request)
    {
        $this->video = $request->route('video');
    }

    /**
     * Determine whether the user can view any models.
     *
     * @param User $user
     * @return Response|bool
     */
    public function viewAny(User $user) : Response|bool
    {
        return $this->video->user()->is($user)
            ? Response::allow()
            : Response::denyWithStatus(404);
    }

    public function create (User $user): Response|bool
    {
        return $this->video->user()->is($user)
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
