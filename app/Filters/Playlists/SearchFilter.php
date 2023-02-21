<?php

namespace App\Filters\Playlists;

use Illuminate\Database\Eloquent\Builder;

class SearchFilter
{
    function __invoke(Builder $query, string $search): Builder
    {
        $match = '%'.$search.'%';

        return $query->where(fn($query) => $query->where('title', 'LIKE', $match)->orWhere('description', 'LIKE', $match));
    }
}
