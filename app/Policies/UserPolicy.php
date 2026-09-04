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
     * Determine whether the user can view another user.
     *
     * @param User|null $user
     * @param User $model
     * @return Response|bool
     */
    public function show(?User $user, User $model): Response|bool
    {
        return $model->is_active || $user?->is($model)
            ? Response::allow()
            : Response::denyWithStatus(404, 'This user doesn\'t exist');
    }

    /**
     * Determine whether the user can subscribe to another user.
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
     * Determine whether the user can report another user.
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
     * Determine whether admin can ban a user.
     *
     * @param User $user
     * @param User $model
     * @return Response|bool
     */
    public function ban (User $user, User $model): Response|bool
    {
        return !$model->is_premium && !$model->is_admin && !$model->is_banned && !$model->trashed();
    }

    /**
     * Determine whether an admin can impersonate another user.
     *
     * @param User $user
     * @param User $model
     * @return Response|bool
     */
    public function impersonate (User $user, User $model): Response|bool
    {
        return $user->canImpersonate() && $model->canBeImpersonated();
    }

    /**
     * Determine whether the user can subscribe to a premium plan.
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
        return !$model->is_premium && !$model->is_admin && !$model->trashed();
    }

    /**
     * Determine whether a user can view another user avatar.
     *
     * @param User|null $user
     * @param User $model
     * @return Response|bool
     */
    public function avatar (?User $user, User $model): Response|bool
    {
        return $model->is_active || $user?->is($model);
    }

    /**
     * Determine whether a user can view another user banner.
     *
     * @param User|null $user
     * @param User $model
     * @return Response|bool
     */
    public function banner (?User $user, User $model): Response|bool
    {
        return $model->is_active || $user?->is($model);
    }
}
