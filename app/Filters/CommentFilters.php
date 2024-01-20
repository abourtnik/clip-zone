<?php

namespace App\Filters;

use App\Filters\Drivers\MySQLFilter;
use App\Filters\Traits\DateFilter;
use App\Filters\Traits\UserFilter;
use Illuminate\Database\Eloquent\Builder;

class CommentFilters extends MySQLFilter
{
    use DateFilter, UserFilter;

    protected string $dateField = 'comments.created_at';

    public function search(string $search): Builder
    {
        $match = '%'.$search.'%';

        return $this->builder->where('content', 'LIKE', $match);
    }

    public function replies (string $value): Builder
    {
        return $this->builder
            ->when($value === 'with', fn($query) => $query->has('replies'))
            ->when($value === 'without', fn($query) => $query->doesntHave('replies'));
    }

    public function ban (string $value): Builder
    {
        return $this->builder
            ->when($value === 'banned', fn($query) => $query->whereNotNull('banned_at'))
            ->when($value === 'not_banned', fn($query) => $query->whereNull('banned_at'));
    }

    public function video(string $value): Builder
    {
        return $this->builder->whereRelation('video', 'id', $value);
    }
}
