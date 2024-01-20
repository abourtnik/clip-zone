<?php

namespace App\Filters;

use App\Filters\Drivers\MySQLFilter;
use App\Filters\Traits\DateFilter;
use Illuminate\Database\Eloquent\Builder;

class UserFilters extends MySQLFilter
{
    use DateFilter;

    public function search(string $search): Builder
    {
        $match = '%'.$search.'%';

        return $this->builder->where(fn($query) => $query
            ->where('username', 'LIKE', $match)
            ->orWhere('email', 'LIKE', $match)
            ->orWhere('description', 'LIKE', $match)
        );
    }

    public function status (string $value): Builder
    {
        return $this->builder
            ->when($value === 'banned', fn($query) => $query->whereNotNull('banned_at'))
            ->when($value === 'not_confirmed', fn($query) => $query->whereNull('email_verified_at'))
            ->when($value === 'premium', fn($query) => $query->whereHas('premium_subscription', function (Builder $query) {
                $query->active();
            }));
    }
}
