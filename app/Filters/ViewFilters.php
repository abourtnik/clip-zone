<?php

namespace App\Filters;

use App\Filters\Traits\DateFilter;

class ViewFilters extends Filter
{
    use DateFilter;

    protected string $dateField = 'view_at';
}
