<?php

namespace App\Filters;

use App\Enums\VideoStatus;
use App\Filters\Drivers\MySQLFilter;
use App\Filters\Traits\DateFilter;
use App\Filters\Traits\UserFilter;
use Illuminate\Contracts\Database\Query\Expression;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class VideoFilters extends MySQLFilter
{
    use DateFilter, UserFilter;

    protected function getDateField(): string|Expression
    {
        $private  = VideoStatus::PRIVATE->value;
        $unlisted = VideoStatus::UNLISTED->value;
        $draft    = VideoStatus::DRAFT->value;
        $failed   = VideoStatus::FAILED->value;
        $planned  = VideoStatus::PLANNED->value;

        return DB::raw("CASE
            WHEN status IN ($private, $unlisted, $draft, $failed) THEN created_at
            WHEN status = $planned THEN scheduled_at
            ELSE published_at
        END");
    }

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
