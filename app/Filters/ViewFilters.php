<?php

namespace App\Filters;

use App\Filters\Drivers\MySQLFilter;
use App\Filters\Traits\DateFilter;

class ViewFilters extends MySQLFilter
{
    use DateFilter;

    protected string $dateField = 'view_at';
}
