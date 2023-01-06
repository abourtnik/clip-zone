<?php

namespace App\Filters\Videos;

use Illuminate\Database\Eloquent\Builder;

class CategoryFilter
{
    function __invoke(Builder $query, string $value): Builder
    {
        return $query
            ->when($value === 'without', fn($query) => $query->doesntHave('category'))
            ->when($value !== 'without', fn($query) => $query->whereRelation('category', 'id', $value));
    }
}
