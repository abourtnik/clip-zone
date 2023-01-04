<?php

namespace App\Filters;

use App\Filters\Interaction\DateFilter;

class InteractionFilters extends AbstractFilters
{
    protected array $filters = [
        'date' => DateFilter::class,
    ];
}
