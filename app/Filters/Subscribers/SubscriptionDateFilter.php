<?php

namespace App\Filters\Subscribers;

use Illuminate\Database\Eloquent\Builder;

class SubscriptionDateFilter
{
    function __invoke(Builder $query, array $dates): Builder
    {
        $start = $dates[0] ?? null;
        $end = $dates[1] ?? null;

        return $query
            ->when($start, fn($query) => $query->where('subscribe_at', '>=', $start))
            ->when($end, fn($query) => $query->where('subscribe_at', '<=', $end));
    }
}
