<?php

namespace App\Listeners;

use App\Events\Video\VideoBanned;
use App\Events\Video\VideoError;
use App\Events\Video\VideoPublished;
use App\Events\Video\VideoUploaded;
use App\Events\Video\VideoViewed;
use App\Notifications\Activity\NewVideo;
use App\Notifications\Video\VideoBanned as VideoBannedNotification;
use App\Notifications\Video\VideoUploaded as VideoUploadedNotification;
use App\Notifications\Video\VideoError as VideoErrorNotification;
use Illuminate\Events\Dispatcher;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;

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
     * Handle video view events.
     */
    public function insertView(VideoViewed $event): void {

        $video = $event->video;
        $ip = request()->getClientIp();

        RateLimiter::attempt('show-video:'.$video->id.':'.$ip, $perMinute = 1, function() use ($video, $ip) {
            $video->increment('views');
            $video->viewsHistory()->create([
                'ip' => $ip,
                'user_id' => Auth::user()?->id
            ]);
        });
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
            VideoViewed::class => 'insertView',
        ];
    }
}
