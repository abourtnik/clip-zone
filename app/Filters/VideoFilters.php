<?php

namespace App\Filters;

use App\Filters\Videos\PublicationDateFilter;
use App\Filters\Videos\SearchFilter;
use App\Filters\Videos\StatusFilter;
use App\Filters\Videos\CategoryFilter;

class VideoFilters extends AbstractFilters
{
    protected array $filters = [
        'search' => SearchFilter::class,
        'status' => StatusFilter::class,
        'category' => CategoryFilter::class,
        'date' => PublicationDateFilter::class,
    ];
}
