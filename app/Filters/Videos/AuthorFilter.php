<?php

namespace App\Filters\Videos;

use Illuminate\Database\Eloquent\Builder;

class AuthorFilter
{
    function __invoke(Builder $query, string $value): Builder
    {
        return $query->whereRelation('user', 'id', $value);
    }
}
