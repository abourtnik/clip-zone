<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Playlist;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class PlaylistPolicy
{
    use HandlesAuthorization;

    public const array ADMIN_ABILITIES = ['show'];

    public function before(?User $user, string $ability): bool|null
    {
        if (in_array($ability, self::ADMIN_ABILITIES) && $user?->is_admin) {
            return true;
        }

        return null;
    }

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

    /**
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @param Playlist $playlist
     * @return Response|bool
     */
    public function view(User $user, Playlist $playlist): Response|bool
    {
        return true;
    }

    /**
     * Determine whether the user can view Playlist.
     *
     * @param User|null $user
     * @param Playlist $playlist
     * @return Response|bool
     */
    public function show(?User $user, Playlist $playlist): Response|bool
    {
        return $playlist->is_active || $playlist->user()->is($user) || $playlist->user()->is(auth('sanctum')->user())
            ? Response::allow()
            : Response::denyWithStatus(404, 'This playlist is private');
    }

    /**
     * Determine whether the user can create the model.
     *
     * @param  User $user
     * @return Response|bool
     */
    public function create(User $user): Response|bool
    {
        return true;
    }


    /**
     * Determine whether the user can update the model.
     *
     * @param  User $user
     * @param  Playlist $playlist
     * @return Response|bool
     */
    public function update(User $user, Playlist $playlist) : Response|bool
    {
        return $playlist->user->is($user)
            ? Response::allow()
            : Response::denyWithStatus(403);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  User $user
     * @param  Playlist $playlist
     * @return Response|bool
     */
    public function delete(User $user, Playlist $playlist): Response|bool
    {
        return $playlist->is_deletable && $playlist->user->is($user)
            ? Response::allow()
            : Response::denyWithStatus(403);
    }

    /**
     * Determine whether the user can favorite the playlist.
     *
     * @param  User $user
     * @param  Playlist $playlist
     * @return Response|bool
     */
    public function favorite(User $user, Playlist $playlist): Response|bool
    {
        return ($playlist->is_active || $playlist->user()->is($user) || $playlist->user()->is(auth('sanctum')->user())) && $playlist->is_deletable
            ? Response::allow()
            : Response::denyWithStatus(404, 'This playlist is private');
    }
}
