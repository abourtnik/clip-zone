<?php

namespace App\View\Components;

use App\Enums\SubtitleStatus;
use App\Models\Subtitle;
use App\Models\Video;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Subtitles extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public ?string $videoId,
    )
    {}

    public function subtitles(): string
    {
        if (!$this->videoId) {
            return json_encode([]);
        }

        return Subtitle::query()
            ->select(['id', 'name', 'language', 'file', 'status', 'video_id'])
            ->where('video_id', $this->videoId)
            ->get()
            ->append('file_url')
            ->toJson();
    }

    public function subtitlesStatus(): array
    {
        return SubtitleStatus::get();
    }

    public function languages(): array
    {
        return Video::languages();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        return view('components.subtitles');
    }
}
