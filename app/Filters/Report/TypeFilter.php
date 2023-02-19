<?php

namespace App\Filters\Report;

use App\Models\Comment;
use App\Models\User;
use App\Models\Video;
use Illuminate\Database\Eloquent\Builder;

class TypeFilter
{
    function __invoke(Builder $query, string $value): Builder
    {
        return $query
            ->when($value === 'video', fn($q) => $q->whereHasMorph('reportable', [Video::class]))
            ->when($value === 'comment', fn($q) => $q->whereHasMorph('reportable', [Comment::class]))
            ->when($value === 'user', fn($q) => $q->whereHasMorph('reportable', [User::class]));
    }
}
