<?php

namespace App\Listeners;

use App\Events\VideoBanned;
use App\Events\VideoPublished;
use App\Events\VideoUploaded;
use App\Notifications\BannedVideo;
use App\Notifications\UserNotification;
use Illuminate\Events\Dispatcher;

class VideoEventSubscriber
{
    /**
     * Handle video published events.
     */
    public function sendVideoPublishedNotification(VideoPublished $event): void {

        foreach ($event->video->user->subscribers as $subscriber) {
            $subscriber->notify(new UserNotification(
                $event->video->user->username. ' has published new video !',
                route('video.show', $event->video)
            ));
        }
    }

    /**
     * Handle video banned events.
     */
    public function sendVideoBannedNotification(VideoBanned $event): void {

        $event->video->user->notify(new BannedVideo($event->video));
    }

    /**
     * Handle video uploaded events.
     */
    public function sendVideoUploadedNotification(VideoUploaded $event): void {

        $event->video->user->notify(new UserNotification(
            'Your video : ' .$event->video->title. ' was uploaded.',
            $event->video->is_draft ? route('user.videos.create', $event->video) : route('user.videos.edit', $event->video)
        ));
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @return array<string, string>
     */
    public function subscribe(Dispatcher $events): array
    {
        return [
            VideoPublished::class => 'sendVideoPublishedNotification',
            VideoBanned::class => 'sendVideoBannedNotification',
            VideoUploaded::class => 'sendVideoUploadedNotification',
        ];
    }
}
