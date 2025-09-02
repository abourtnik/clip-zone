<?php

namespace App\Filters;

use App\Filters\Drivers\MeilisearchFilter;

class SearchFilters extends MeilisearchFilter
{
    public function duration (string $duration): string {
        return match ($duration) {
            '4' => 'duration < 240',
            '4-20' => 'duration 240 TO 1200',
            '20' => 'duration > 1200',
        };
    }

    public function date (string $duration): string {
        return match ($duration) {
            'hour' => 'published_at ' .now()->subHour()->timestamp. ' TO '. now()->timestamp,
            'today' => 'published_at ' .now()->startOfDay()->timestamp. ' TO '. now()->timestamp,
            'week' => 'published_at ' .now()->startOfWeek()->timestamp. ' TO '. now()->timestamp,
            'month' => 'published_at ' .now()->startOfMonth()->timestamp. ' TO '. now()->timestamp,
            'year' => 'published_at ' .now()->startOfYear()->timestamp. ' TO '. now()->timestamp,
        };
    }
}
