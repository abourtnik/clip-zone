<?php

namespace App\Filters\Report;

use Illuminate\Database\Eloquent\Builder;

class ReasonFilter
{
    function __invoke(Builder $query, string $value): Builder
    {
        return $query->where('reason', $value);
    }
}
