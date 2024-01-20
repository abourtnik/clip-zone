<?php

namespace App\Filters;

use App\Filters\Drivers\MySQLFilter;
use App\Filters\Traits\DateFilter;

class InteractionFilters extends MySQLFilter
{
    use DateFilter;

    protected string $dateField = 'perform_at';
}
