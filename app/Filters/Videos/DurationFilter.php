<?php

namespace App\Filters\Videos;

use Illuminate\Database\Eloquent\Builder;

class DurationFilter
{
    function __invoke(Builder $query, string $value): Builder
    {
        return $query
            ->when($value === '4', fn($q) => $q->where('duration' , '<', 240))
            ->when($value === '4-20', fn($q) => $q->whereBetween('duration' , [240, 1200]))
            ->when($value === '20', fn($q) => $q->where('duration' , '>', 1200))
            ;
    }
}
