<?php

namespace App\Filters;

use App\Filters\Traits\DateFilter;
use App\Filters\Traits\UserFilter;
use Illuminate\Database\Eloquent\Builder;

class PlaylistFilters extends Filter
{
    use DateFilter, UserFilter;

    public function search(string $search): Builder
    {
        $match = '%'.$search.'%';

        return $this->builder->where(fn($query) => $query->where('title', 'LIKE', $match)->orWhere('description', 'LIKE', $match));
    }

    public function status(string $status): Builder
    {
        return $this->builder->where('status', $status);
    }
}
