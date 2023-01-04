<?php

namespace App\Filters\Subscribers;

use Illuminate\Database\Eloquent\Builder;

class SearchFilter
{
    function __invoke(Builder $query, string $search): Builder
    {
        $match = '%'.$search.'%';

        return $query->where('username' , 'LIKE', $match);
    }
}
