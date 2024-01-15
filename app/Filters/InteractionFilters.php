<?php

namespace App\Filters;

use App\Filters\Traits\DateFilter;

class InteractionFilters extends Filter
{
    use DateFilter;

    protected string $dateField = 'perform_at';
}
