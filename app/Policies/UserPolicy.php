<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view other user.
     *
     * @param User|null $user
     * @param User $model
     * @return Response|bool
     */
    public function show(?User $user, User $model): Response|bool
    {
        return $model->is_active
            ? Response::allow()
            : Response::denyWithStatus(404, 'This user don\'t exist');
    }

    /**
     * Determine whether the user can subscribe other user.
     *
     * @param User $user
     * @param User $model
     * @return Response|bool
     */
    public function subscribe (User $user, User $model): Response|bool
    {
        return $user->isNot($model)
            ? Response::allow()
            : Response::denyWithStatus(403, 'You can\'t subscribe yourself');
    }
}
