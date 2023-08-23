<?php

namespace App\Filters\Users;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class StatusFilter
{
    function __invoke(Builder $query, string $value): Builder
    {
        return $query
            ->when($value === 'banned', fn($query) => $query->whereNotNull('banned_at'))
            ->when($value === 'not_confirmed', fn($query) => $query->whereNull('email_verified_at'))
            ->when($value === 'premium', fn($query) => $query->whereHas('premium_subscription', function (Builder $query) {
                $query->whereNull('ends_at')->orWhere('ends_at', '>', Carbon::now());
            }));
    }
}
