<?php

namespace App\Filters\Report;

use Illuminate\Database\Eloquent\Builder;

class StatusFilter
{
    function __invoke(Builder $query, string $value): Builder
    {
        return $query->where('status', $value);
    }
}