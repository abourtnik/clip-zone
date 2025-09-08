<?php

namespace App\Filters;

use App\Enums\VideoStatus;
use App\Filters\Drivers\MySQLFilter;
use App\Filters\Traits\DateFilter;
use App\Filters\Traits\UserFilter;
use Illuminate\Database\Eloquent\Builder;

class VideoFilters extends MySQLFilter
{
    use DateFilter, UserFilter;

    protected string $dateField = 'published_at';

    public function search(string $search): Builder
    {
        $match = '%'.$search.'%';

        return $this->builder->where(fn($query) => $query->where('title', 'LIKE', $match)->orWhere('description', 'LIKE', $match));
    }

    public function status(string $status): Builder
    {
        return $this->builder->when($status == VideoStatus::PUBLIC->value, fn(Builder $query) => $query->scopes('active'))
            ->when($status == VideoStatus::PLANNED->value, fn($query) => $query->where('status', VideoStatus::PLANNED->value)->where('scheduled_at', '>', now()))
            ->when(in_array($status, [VideoStatus::PRIVATE->value, VideoStatus::UNLISTED->value, VideoStatus::BANNED->value, VideoStatus::DRAFT->value, VideoStatus::FAILED->value]), fn($query) => $query->where('status', $status));
    }

    public function category(string $category): Builder
    {
        return $this->builder->when($category === 'without', fn($query) => $query->doesntHave('category'))
            ->when($category !== 'without', fn($query) => $query->whereRelation('category', 'id', $category));
    }
}
