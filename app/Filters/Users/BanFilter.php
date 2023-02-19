<?php

namespace App\Filters\Users;

use Illuminate\Database\Eloquent\Builder;

class BanFilter
{
    function __invoke(Builder $query, string $value): Builder
    {
        return $query
            ->when($value === 'banned', fn($query) => $query->whereNotNull('banned_at'))
            ->when($value === 'not_banned', fn($query) => $query->whereNull('banned_at'));
    }
}
