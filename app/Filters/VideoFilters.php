<?php

namespace App\Filters;

use App\Filters\Videos\PublicationDateFilter;
use App\Filters\Videos\SearchFilter;
use App\Filters\Videos\StatusFilter;

class VideoFilters extends AbstractFilters
{
    protected array $filters = [
        'search' => SearchFilter::class,
        'status' => StatusFilter::class,
        'date' => PublicationDateFilter::class,
    ];
}
