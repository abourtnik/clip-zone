<?php

namespace App\Listeners;

use App\Events\VideoBanned;
use App\Notifications\BannedVideo;

class SendVideoBannedNotification
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
     * @param VideoBanned $event
     * @return void
     */
    public function handle(VideoBanned $event) : void
    {
        $event->video->user->notify(new BannedVideo($event->video));
    }
}
