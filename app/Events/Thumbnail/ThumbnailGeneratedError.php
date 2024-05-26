<?php

namespace App\Events\Thumbnail;

use App\Models\Video;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ThumbnailGeneratedError
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
}
