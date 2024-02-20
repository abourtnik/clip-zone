<?php

namespace App\Policies;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class NotificationPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can read notification.
     *
     * @param User $user
     * @param Notification $notification
     * @return Response|bool
     */
    public function read(User $user, Notification $notification): Response|bool
    {
        return $notification->user()->is($user)
            ? Response::allow()
            : Response::denyWithStatus(403, 'You are not authorized to perform this action for this record.');
    }

    /**
     * Determine whether the user can unread notification.
     *
     * @param User $user
     * @param Notification $notification
     * @return Response|bool
     */
    public function unread(User $user, Notification $notification): Response|bool
    {
        return $notification->user()->is($user)
            ? Response::allow()
            : Response::denyWithStatus(403, 'You are not authorized to perform this action for this record.');
    }

    /**
     * Determine whether the user can view click on notification.
     *
     * @param User $user
     * @param Notification $notification
     * @return Response|bool
     */
    public function click(User $user, Notification $notification): Response|bool
    {
        return $notification->user()->is($user)
            ? Response::allow()
            : Response::denyWithStatus(403, 'You are not authorized to perform this action for this record.');
    }

    /**
     * Determine whether the user can delete notification.
     *
     * @param User $user
     * @param Notification $notification
     * @return Response|bool
     */
    public function delete(User $user, Notification $notification): Response|bool
    {
        return $notification->user()->is($user)
            ? Response::allow()
            : Response::denyWithStatus(403, 'You are not authorized to perform this action for this record.');
    }
}
