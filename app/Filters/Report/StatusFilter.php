<?php

namespace App\Filters\Report;

use Illuminate\Database\Eloquent\Builder;

class StatusFilter
{
    function __invoke(Builder $query, array $dates): Builder
    {
        $start = $dates[0] ?? null;
        $end = $dates[1] ?? null;

        return $query
            ->when($start, fn($query) => $query->where('created_at', '>=', $start))
            ->when($end, fn($query) => $query->where('created_at', '<=', $end));
    }
}
