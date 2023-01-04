<?php

namespace App\Filters\Comments;

use Illuminate\Database\Eloquent\Builder;

class SearchFilter
{
    function __invoke(Builder $query, string $search): Builder
    {
        $match = '%'.$search.'%';

        return $query->where('content', 'LIKE', $match);
    }
}
