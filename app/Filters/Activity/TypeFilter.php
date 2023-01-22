<?php

namespace App\Filters\Activity;

use App\Models\Comment;
use App\Models\Interaction;
use Illuminate\Database\Eloquent\Builder;

class TypeFilter
{
    function __invoke(Builder $query, string $value): Builder
    {
        return $query
            ->when($value === 'comments', fn($q) => $q->where('subject_type', Comment::class))
            ->when($value === 'interactions', fn($q) => $q->where('subject_type', Interaction::class))
            ->when($value === 'likes', fn($q) => $q->where('subject_type', Interaction::class)->where('properties->status', true))
            ->when($value === 'dislikes', fn($q) => $q->where('subject_type', Interaction::class)->where('properties->status', false));
    }
}
