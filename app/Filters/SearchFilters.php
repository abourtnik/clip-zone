<?php

namespace App\Filters;

use App\Filters\Videos\DurationFilter;
use App\Filters\Videos\UploadFilter;

class SearchFilters extends AbstractFilters
{
    protected array $filters = [
        'duration' => DurationFilter::class,
        'date' => UploadFilter::class,
    ];
}
