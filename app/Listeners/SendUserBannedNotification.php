<?php

namespace App\Listeners;

use App\Events\UserBanned;
use App\Notifications\BannedUser;

class SendUserBannedNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param UserBanned $event
     * @return void
     */
    public function handle(UserBanned $event) : void
    {
        $event->user->notify(new BannedUser());
    }
}
