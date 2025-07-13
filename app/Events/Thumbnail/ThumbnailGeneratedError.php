<?php

namespace App\Events\Thumbnail;

use App\Models\Thumbnail;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ThumbnailGeneratedError  implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Thumbnail $thumbnail;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Thumbnail $thumbnail)
    {
        $this->thumbnail = $thumbnail;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return PrivateChannel
     */
    public function broadcastOn(): PrivateChannel
    {
        return new PrivateChannel('App.Models.User.' .$this->thumbnail->video->user_id);
    }

    public function broadcastAs(): string
    {
        return 'thumbnail.error';
    }

    public function broadcastWith(): array {

        return [
            'id' => $this->thumbnail->id,
        ];
    }
}
