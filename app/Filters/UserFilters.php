<?php

namespace App\Filters;

use App\Filters\Users\DateFilter;
use App\Filters\Users\SearchFilter;
use App\Filters\Users\BanFilter;

class UserFilters extends AbstractFilters
{
    protected array $filters = [
        'search' => SearchFilter::class,
        'date' => DateFilter::class,
        'ban' => BanFilter::class,
    ];
}
