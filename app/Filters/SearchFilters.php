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
            'hour' => 'publication_date ' .now()->subHour()->timestamp. ' TO '. now()->timestamp,
            'today' => 'publication_date ' .now()->startOfDay()->timestamp. ' TO '. now()->timestamp,
            'week' => 'publication_date ' .now()->startOfWeek()->timestamp. ' TO '. now()->timestamp,
            'month' => 'publication_date ' .now()->startOfMonth()->timestamp. ' TO '. now()->timestamp,
            'year' => 'publication_date ' .now()->startOfYear()->timestamp. ' TO '. now()->timestamp,
        };
    }
}
