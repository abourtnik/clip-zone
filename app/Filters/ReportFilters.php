<?php

namespace App\Filters;

use App\Filters\Drivers\MySQLFilter;
use App\Filters\Traits\DateFilter;
use App\Filters\Traits\UserFilter;
use App\Models\Comment;
use App\Models\User;
use App\Models\Video;
use Illuminate\Database\Eloquent\Builder;

class ReportFilters extends MySQLFilter
{
    use DateFilter, UserFilter;

    public function search(string $search): Builder
    {
        $match = '%'.$search.'%';

        return $this->builder->where(function($query) use ($match) {
            return $query->where('reason', 'LIKE', $match)
                ->orWhere('comment', 'LIKE', $match)
                ->OrWhereHasMorph('reportable', [Video::class], fn($q) => $q->where('title', 'LIKE', $match)->orWhereRelation('user', 'username', 'LIKE', $match));
        });
    }

    public function type(string $type): Builder
    {
        return $this->builder->when($type === 'video', fn($q) => $q->whereHasMorph('reportable', [Video::class]))
            ->when($type === 'comment', fn($q) => $q->whereHasMorph('reportable', [Comment::class]))
            ->when($type === 'user', fn($q) => $q->whereHasMorph('reportable', [User::class]));
    }

    public function reason(string $reason): Builder
    {
        return $this->builder->where('reason', $reason);
    }

    public function status(string $status): Builder
    {
        return $this->builder->where('status', $status);
    }
}
