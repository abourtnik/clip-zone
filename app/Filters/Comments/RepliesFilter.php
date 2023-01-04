<?php

namespace App\Filters\Comments;

use Illuminate\Database\Eloquent\Builder;

class RepliesFilter
{
    function __invoke(Builder $query, string $value): Builder
    {
        return $query
            ->when($value === 'with', fn($query) => $query->has('replies'))
            ->when($value === 'without', fn($query) => $query->doesntHave('replies'));
    }
}
