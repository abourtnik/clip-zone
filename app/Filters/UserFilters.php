<?php

namespace App\Filters;

use App\Filters\Users\DateFilter;
use App\Filters\Users\SearchFilter;
use App\Filters\Users\StatusFilter;

class UserFilters extends AbstractFilters
{
    protected array $filters = [
        'search' => SearchFilter::class,
        'date' => DateFilter::class,
        'status' => StatusFilter::class,
    ];
}
