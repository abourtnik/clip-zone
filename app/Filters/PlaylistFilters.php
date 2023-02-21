<?php

namespace App\Filters;

use App\Filters\Playlists\DateFilter;
use App\Filters\Playlists\StatusFilter;
use App\Filters\Playlists\SearchFilter;

class PlaylistFilters extends AbstractFilters
{
    protected array $filters = [
        'search' => SearchFilter::class,
        'status' => StatusFilter::class,
        'date' => DateFilter::class,
    ];
}
