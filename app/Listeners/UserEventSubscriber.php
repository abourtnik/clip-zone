<?php

namespace App\Listeners;

use App\Events\User\UserBanned;
use App\Events\User\UserSubscribed;
use App\Notifications\Account\BanAccount;
use App\Notifications\Activity\NewSubscriber;

class UserEventSubscriber
{
    /**
     * Handle user has new subscriber events.
     */
    public function handleUserSubscribed(UserSubscribed $event): void
    {
        $event->user->notify(new NewSubscriber($event->subscriber));
    }

    /**
     * Handle user banned events.
     */
    public function handleUserBanned(UserBanned $event): void
    {
        $event->user->notify(new BanAccount());
    }
}
