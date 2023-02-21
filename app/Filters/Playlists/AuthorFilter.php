<?php

namespace App\Filters\Playlists;

use Illuminate\Database\Eloquent\Builder;

class AuthorFilter
{
    function __invoke(Builder $query, string $value): Builder
    {
        return $query->whereRelation('user', 'id', $value);
    }
}
