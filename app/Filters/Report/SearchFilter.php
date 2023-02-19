<?php

namespace App\Filters\Report;

use App\Models\Video;
use Illuminate\Database\Eloquent\Builder;

class SearchFilter
{
    function __invoke(Builder $query, string $search): Builder
    {
        $match = '%'.$search.'%';

        return $query->where(function($query) use ($match) {
            return $query->where('reason', 'LIKE', $match)
                ->orWhere('comment', 'LIKE', $match)
                ->OrWhereHasMorph('reportable', [Video::class], fn($q) => $q->where('title', 'LIKE', $match)->orWhereRelation('user', 'username', 'LIKE', $match));
        });
    }
}
