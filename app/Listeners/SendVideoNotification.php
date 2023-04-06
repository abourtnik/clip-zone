<?php

namespace App\Listeners;

use App\Events\VideoPublished;
use App\Notifications\UserNotification;

class SendVideoNotification
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
     * @param VideoPublished  $event
     * @return void
     */
    public function handle(VideoPublished $event) : void
    {
        foreach ($event->video->user->subscribers as $subscriber) {
            $subscriber->notify(new UserNotification(
                $event->video->user->username. ' has published new video !',
                route('video.show', $event->video)
            ));
        }
    }
}
