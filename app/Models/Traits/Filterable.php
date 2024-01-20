<?php

namespace App\Models\Traits;

use App\Filters\Drivers\MySQLFilter;
use Illuminate\Database\Eloquent\Builder;

trait Filterable
{
    /**
     * Apply all relevant filters.
     *
     * @param Builder $query
     * @param MySQLFilter $filter
     * @return Builder
     */
    public function scopeFilter(Builder $query, MySQLFilter $filter) : Builder
    {
        return $filter->apply($query);
    }
}
