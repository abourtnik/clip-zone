<?php

namespace App\Filters;

use App\Filters\Traits\DateFilter;
use Illuminate\Database\Eloquent\Builder;

class SubscriberFilters extends Filter
{
    use DateFilter;

    protected string $dateField = 'subscribe_at';

    public function search(string $search): Builder
    {
        $match = '%'.$search.'%';

        return $this->builder->where('username' , 'LIKE', $match);
    }
}
