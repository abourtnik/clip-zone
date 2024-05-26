<?php

namespace App\Events\Thumbnail;

use App\Models\Thumbnail;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Broadcasting\PrivateChannel;

class ThumbnailGenerated implements ShouldBroadcast
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

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'thumbnail.generated';
    }

    public function broadcastWith(): array {

        return [
            'id' => $this->thumbnail->id,
            'url' => $this->thumbnail->url,
            'status' => $this->thumbnail->status,
            'is_active' => $this->thumbnail->is_active,
        ];
    }
}
