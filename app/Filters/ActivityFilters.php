<?php

namespace App\Filters;

use App\Filters\Activity\DateFilter;
use App\Filters\Activity\TypeFilter;

class ActivityFilters extends AbstractFilters
{
    protected array $filters = [
        'type' => TypeFilter::class,
        'date' => DateFilter::class,
    ];
}
