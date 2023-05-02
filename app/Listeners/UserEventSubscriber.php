<?php

namespace App\Listeners;

use App\Events\UserBanned;
use App\Events\UserSubscribed;
use App\Notifications\BannedUser;
use App\Notifications\UserNotification;
use Illuminate\Events\Dispatcher;

class UserEventSubscriber
{
    /**
     * Handle user have new subscriber events.
     */
    public function sendUserSubscribedNotification(UserSubscribed $event): void {
        $event->user->notify(new UserNotification(
            $event->subscriber->username. ' has subscribed to your channel !',
            route('user.show', $event->subscriber)
        ));
    }

    /**
     * Handle user banned events.
     */
    public function sendUserBannedNotification(UserBanned $event): void {

        $event->user->notify(new BannedUser());
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @return array<string, string>
     */
    public function subscribe(Dispatcher $events): array
    {
        return [
            UserSubscribed::class => 'sendUserSubscribedNotification',
            UserBanned::class => 'sendUserBannedNotification',
        ];
    }
}
