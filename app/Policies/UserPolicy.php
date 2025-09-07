<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    use HandlesAuthorization;

    public const array ADMIN_ABILITIES = ['avatar', 'banner'];

    public function before(?User $user, string $ability): bool|null
    {
        if (in_array($ability, self::ADMIN_ABILITIES) && $user?->is_admin) {
            return true;
        }

        return null;
    }

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
        return !$model->is_premium && !$model->is_admin && !$model->is_banned;
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

    /**
     * Determine whether admin can delete user.
     *
     * @param User $user
     * @param User $model
     * @return Response|bool
     */
    public function delete (User $user, User $model): Response|bool
    {
        return !$model->is_premium && !$model->is_admin;
    }

    /**
     * Determine whether user can view other user avatar.
     *
     * @param User|null $user
     * @param User $model
     * @return Response|bool
     */
    public function avatar (?User $user, User $model): Response|bool
    {
        return $model->is_active;
    }

    /**
     * Determine whether user can view other user banner.
     *
     * @param User|null $user
     * @param User $model
     * @return Response|bool
     */
    public function banner (?User $user, User $model): Response|bool
    {
        return $model->is_active;
    }
}
