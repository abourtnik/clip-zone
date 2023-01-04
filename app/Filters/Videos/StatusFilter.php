<?php

namespace App\Filters\Videos;

use Illuminate\Database\Eloquent\Builder;

class StatusFilter
{
    function __invoke(Builder $query, string $status): Builder
    {
        return $query->where('status', $status);
    }
}
