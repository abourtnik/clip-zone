<?php

namespace App\Filters\Comments;

use Illuminate\Database\Eloquent\Builder;

class VideoFilter
{
    function __invoke(Builder $query, string $value): Builder
    {
        return $query->whereRelation('video', 'id', $value);
    }
}
