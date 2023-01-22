<?php

namespace App\Filters\Videos;

use Illuminate\Database\Eloquent\Builder;

class UploadFilter
{
    function __invoke(Builder $query, string $value): Builder
    {
        return $query
            ->when($value === 'hour', fn($q) => $q->whereBetween('publication_date' , [now()->subHour(), now()]))
            ->when($value === 'today', fn($q) => $q->whereDate('publication_date', now()))
            ->when($value === 'week', fn($q) => $q->whereBetween('publication_date', [now()->startOfWeek(), now()->endOfWeek()]))
            ->when($value === 'month', fn($q) => $q->whereBetween('publication_date', [now()->startOfMonth(), now()->endOfMonth()]))
            ->when($value === 'year', fn($q) => $q->whereYear('publication_date' , now()->year))
            ;
    }
}
