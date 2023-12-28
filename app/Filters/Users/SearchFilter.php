<?php

namespace App\Filters\Users;

use Illuminate\Database\Eloquent\Builder;

class SearchFilter
{
    function __invoke(Builder $query, string $search): Builder
    {
        $match = '%'.$search.'%';

        return $query->where(fn($query) => $query
            ->where('username', 'LIKE', $match)
            ->orWhere('email', 'LIKE', $match)
            ->orWhere('description', 'LIKE', $match)
        );
    }
}
