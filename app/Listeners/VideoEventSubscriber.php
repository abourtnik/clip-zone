<?php

namespace App\Listeners;

use App\Events\Video\VideoBanned;
use App\Events\Video\VideoError;
use App\Events\Video\VideoPublished;
use App\Events\Video\VideoUploaded;
use App\Events\Video\VideoViewed;
use App\Notifications\BannedVideo;
use App\Notifications\UserNotification;
use Illuminate\Events\Dispatcher;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;

class VideoEventSubscriber
{
    /**
     * Handle video published events.
     */
    public function sendVideoPublishedNotification(VideoPublished $event): void {

        foreach ($event->video->user->subscribers as $subscriber) {
            $subscriber->notify(new UserNotification(
                $event->video->user->username. ' has published new video !',
                $event->video->route
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
            'Your video : ' .$event->video->title. ' was successfully processed.',
            $event->video->is_draft ? route('user.videos.create', $event->video) : route('user.videos.edit', $event->video)
        ));
    }

    /**
     * Handle video error events.
     */
    public function sendVideoErrorNotification(VideoError $event): void {

        $event->video->user->notify(new UserNotification(
            'The processing of your video : ' .$event->video->title. ' failed !',
            route('user.videos.index')
        ));
    }

    /**
     * Handle video view events.
     */
    public function insertView(VideoViewed $event): void {

        $video = $event->video;
        $ip = request()->getClientIp();

        RateLimiter::attempt('show-video:'.$video->id.':'.$ip, $perMinute = 1, function() use ($video, $ip) {
            $video->views()->create([
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
