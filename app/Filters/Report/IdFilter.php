<?php

namespace App\Filters\Report;

use Illuminate\Database\Eloquent\Builder;

class IdFilter
{
    function __invoke(Builder $query, string $value): Builder
    {
        return $query->where('reportable_id', $value);
    }
}
