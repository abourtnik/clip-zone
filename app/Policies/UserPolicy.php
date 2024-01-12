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
            : Response::denyWithStatus(404, 'This user doesn\'t exist');
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
        return $user->isNot($model) && $model->is_active
            ? Response::allow()
            : Response::denyWithStatus(403, 'You can\'t subscribe to this user');
    }

    /**
     * Determine whether the user can report other user.
     *
     * @param User $user
     * @param User $model
     * @return Response|bool
     */
    public function report (User $user, User $model): Response|bool
    {
        return $user->isNot($model) && $model->is_active && !$model->isReportedByUser($user)
            ? Response::allow()
            : Response::denyWithStatus(403, 'You are not authorized to report this user');
    }

    /**
     * Determine whether admin can ban user.
     *
     * @param User $user
     * @param User $model
     * @return Response|bool
     */
    public function ban (User $user, User $model): Response|bool
    {
        return !$model->is_premium
            ? Response::allow()
            : Response::denyWithStatus(403, 'You can\'t ban premium user');
    }

    /**
     * Determine whether the user can subscribe to premium plan.
     *
     * @param User $user
     * @return Response|bool
     */
    public function premiumSubscribe (User $user): Response|bool
    {
        return $user->has_current_subscription
            ? Response::denyWithStatus(403, "You already have an active subscription.")
            : Response::allow();
    }
}
