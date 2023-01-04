<?php

namespace App\Filters;

use App\Filters\Views\DateFilter;

class ViewFilters extends AbstractFilters
{
    protected array $filters = [
        'date' => DateFilter::class,
    ];
}
