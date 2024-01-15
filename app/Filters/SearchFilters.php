<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;

class SearchFilters extends Filter
{
    public function duration(string $duration): Builder
    {
        return $this->builder
            ->when($duration === '4', fn($q) => $q->where('duration' , '<', 240))
            ->when($duration === '4-20', fn($q) => $q->whereBetween('duration' , [240, 1200]))
            ->when($duration === '20', fn($q) => $q->where('duration' , '>', 1200));
    }

    public function date(string $value): Builder
    {
        return $this->builder
            ->when($value === 'hour', fn($q) => $q->whereBetween('publication_date' , [now()->subHour(), now()]))
            ->when($value === 'today', fn($q) => $q->whereDate('publication_date', now()))
            ->when($value === 'week', fn($q) => $q->whereBetween('publication_date', [now()->startOfWeek(), now()->endOfWeek()]))
            ->when($value === 'month', fn($q) => $q->whereBetween('publication_date', [now()->startOfMonth(), now()->endOfMonth()]))
            ->when($value === 'year', fn($q) => $q->whereYear('publication_date' , now()->year));
    }
}
