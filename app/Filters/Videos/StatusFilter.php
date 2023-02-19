<?php

namespace App\Filters\Videos;

use App\Enums\VideoStatus;
use Illuminate\Database\Eloquent\Builder;

class StatusFilter
{
    function __invoke(Builder $query, string $status): Builder
    {
        return $query->when($status == VideoStatus::PUBLIC->value, fn($query) => $query->active())
            ->when($status == VideoStatus::PLANNED->value, fn($query) => $query->where('status', VideoStatus::PLANNED->value)->where('scheduled_date', '>', now()))
            ->when(in_array($status, [VideoStatus::PRIVATE->value, VideoStatus::UNLISTED->value]), fn($query) => $query->where('status', $status));
    }
}
