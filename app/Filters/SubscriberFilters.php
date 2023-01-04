<?php

namespace App\Filters;

use App\Filters\Subscribers\SearchFilter;
use App\Filters\Subscribers\SubscriptionDateFilter;

class SubscriberFilters extends AbstractFilters
{
    protected array $filters = [
        'search' => SearchFilter::class,
        'date' => SubscriptionDateFilter::class,
    ];
}
