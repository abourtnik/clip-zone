<?php

namespace App\Events\Video;

use App\Models\Video;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class VideoUploaded implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Video $video;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Video $video)
    {
        $this->video = $video;
    }

    public function broadcastOn(): PrivateChannel {

        return new PrivateChannel('App.Models.User.' .$this->video->user_id);
    }

    public function broadcastAs(): string {

        return 'video.uploaded';
    }

    public function broadcastWith(): array {

        return [
            'url' => $this->video->file_url
        ];
    }
}
