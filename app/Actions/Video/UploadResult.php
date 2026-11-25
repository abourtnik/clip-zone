<?php

namespace App\Actions\Video;

use App\Models\Video;

final readonly class UploadResult
{
    private function __construct(
        public bool $completed,
        public ?Video $video = null,
        public ?int $progress = null,
    ) {}

    public static function inProgress(int $progress): self
    {
        return new self(false, progress: $progress);
    }

    public static function completed(Video $video): self
    {
        return new self(true, video: $video);
    }
}
