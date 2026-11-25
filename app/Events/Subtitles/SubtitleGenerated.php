<?php

namespace App\Events\Subtitles;

use App\Http\Resources\Subtitle\SubtitleListResource;
use App\Models\Subtitle;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Broadcasting\PrivateChannel;

class SubtitleGenerated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Subtitle $subtitle;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Subtitle $subtitle)
    {
        $this->subtitle = $subtitle;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return PrivateChannel
     */
    public function broadcastOn(): PrivateChannel
    {
        return new PrivateChannel('App.Models.User.' .$this->subtitle->video->user_id);
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'subtitle.generated';
    }

    public function broadcastWith(): array {

        return (new SubtitleListResource($this->subtitle))->resolve();
    }
}
