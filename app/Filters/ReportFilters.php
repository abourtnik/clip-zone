<?php

namespace App\Filters;

use App\Filters\Report\DateFilter;
use App\Filters\Report\TypeFilter;
use App\Filters\Report\SearchFilter;
use App\Filters\Report\StatusFilter;
use App\Filters\Report\ReasonFilter;

class ReportFilters extends AbstractFilters
{
    protected array $filters = [
        'search' => SearchFilter::class,
        'type' => TypeFilter::class,
        'reason' => ReasonFilter::class,
        'status' => StatusFilter::class,
        'date' => DateFilter::class,
    ];
}
