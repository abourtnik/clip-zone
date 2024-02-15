<?php

namespace App\Services;

use App\Models\Video;
use Illuminate\Support\Arr;

class SubtitleService
{
    public function getRemainingLanguages (Video $video) : array {

        $usedLanguages = $video->subtitles->pluck('language')->toArray();

        return Arr::where(Video::languages(), fn (string $value, string $key) => !in_array($key, $usedLanguages));
    }
}
