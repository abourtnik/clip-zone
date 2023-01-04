<?php

namespace App\Filters\Interaction;

use Illuminate\Database\Eloquent\Builder;

class DateFilter
{
    function __invoke(Builder $query, array $dates): Builder
    {
        $start = $dates[0] ?? null;
        $end = $dates[1] ?? null;

        return $query
            ->when($start, fn($query) => $query->where('perform_at', '>=', $start))
            ->when($end, fn($query) => $query->where('perform_at', '<=', $end));
    }
}
