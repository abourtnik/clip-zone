<?php

namespace App\Filters;

use App\Filters\Drivers\MySQLFilter;
use App\Filters\Traits\DateFilter;
use Illuminate\Database\Eloquent\Builder;

class SubscriptionFilters extends MySQLFilter
{
    use DateFilter;

    protected string $dateField = 'subscribe_at';

    public function search(string $search): Builder
    {
        $match = '%'.$search.'%';

        return $this->builder->whereRelation('subscriber' ,'username', 'LIKE', $match);
    }
}
