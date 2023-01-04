<?php

namespace App\Filters\Videos;

use Illuminate\Database\Eloquent\Builder;

class PublicationDateFilter
{
    function __invoke(Builder $query, array $dates): Builder
    {
        $start = $dates[0] ?? null;
        $end = $dates[1] ?? null;

        return $query
            ->when($start, fn($query) => $query->where('publication_date', '>=', $start))
            ->when($end, fn($query) => $query->where('publication_date', '<=', $end));
    }
}
