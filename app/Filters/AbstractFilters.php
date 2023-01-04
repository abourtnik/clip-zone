<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;

class AbstractFilters
{
    protected array $filters = [];

    public function apply(Builder $query)
    {
        foreach ($this->receivedFilters() as $name => $value) {
            if (!is_null($value)) {
                $filterInstance = new $this->filters[$name];
                $query = $filterInstance($query, $value);
            }
        }

        return $query;
    }

    public function receivedFilters(): array
    {
        $clear = request()->exists('clear');
        return $clear ? [] : request()->only(array_keys($this->filters));
    }
}
