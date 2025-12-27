<?php

namespace App\Listeners;

use App\Enums\CustomPlaylistType;
use App\Events\Video\VideoBanned;
use App\Events\Video\VideoError;
use App\Events\Video\VideoInteracted;
use App\Events\Video\VideoPublished;
use App\Events\Video\VideoUploaded;
use App\Notifications\Activity\NewVideo;
use App\Notifications\Video\VideoBanned as VideoBannedNotification;
use App\Notifications\Video\VideoUploaded as VideoUploadedNotification;
use App\Notifications\Video\VideoError as VideoErrorNotification;
use App\Playlists\PlaylistManager;
use Illuminate\Events\Dispatcher;

class VideoEventSubscriber
{
    /**
     * Handle video published events.
     */
    public function sendVideoPublishedNotification(VideoPublished $event): void
    {
        foreach ($event->video->user->subscribers as $subscriber) {
            $subscriber->notify(new NewVideo($event->video));
        }
    }

    /**
     * Handle video banned events.
     */
    public function sendVideoBannedNotification(VideoBanned $event): void
    {
        $event->video->user->notify(new VideoBannedNotification($event->video));
    }

    /**
     * Handle video uploaded events.
     */
    public function sendVideoUploadedNotification(VideoUploaded $event): void
    {
        $event->video->user->notify(new VideoUploadedNotification($event->video));
    }

    /**
     * Handle video error events.
     */
    public function sendVideoErrorNotification(VideoError $event): void
    {
        $event->video->user->notify(new VideoErrorNotification($event->video));
    }

    /**
     * Handle video interacted events.
     */
    public function handleVideoInteracted(VideoInteracted $event): void
    {
        if ($event->currentStatus === true || ($event->previousStatus === true && $event->currentStatus === null)) {
            PlaylistManager::get(CustomPlaylistType::LIKED_VIDEOS)
                ->getPlaylist()
                ->touch();
        }
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
            VideoError::class => 'sendVideoErrorNotification',
            VideoInteracted::class => 'handleVideoInteracted',
        ];
    }
}
