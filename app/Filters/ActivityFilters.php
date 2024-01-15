<?php

namespace App\Filters;

use App\Filters\Traits\DateFilter;
use App\Models\Comment;
use App\Models\Interaction;
use App\Models\Video;
use Illuminate\Database\Eloquent\Builder;

class ActivityFilters extends Filter
{
    use DateFilter;

    public function type(string $value): Builder
    {
        return $this->builder->when($value === 'video_likes', fn($q) => $q->whereHasMorph('subject', [Interaction::class], fn($query) => $query->whereHasMorph('likeable', [Video::class])->where('status', true)))
            ->when($value === 'comment_likes', fn($q) => $q->whereHasMorph('subject', [Interaction::class], fn($query) => $query->whereHasMorph('likeable', [Comment::class])->where('status', true)))
            ->when($value === 'video_dislikes', fn($q) => $q->whereHasMorph('subject', [Interaction::class], fn($query) => $query->whereHasMorph('likeable', [Video::class])->where('status', false)))
            ->when($value === 'comment_dislikes', fn($q) => $q->whereHasMorph('subject', [Interaction::class], fn($query) => $query->whereHasMorph('likeable', [Comment::class])->where('status', false)))
            ->when($value === 'comments', fn($q) => $q->where('subject_type', Comment::class))
            ->when($value === 'interactions', fn($q) => $q->where('subject_type', Interaction::class))
            ->when($value === 'likes', fn($q) => $q->where('subject_type', Interaction::class)->where('properties->status', true))
            ->when($value === 'dislikes', fn($q) => $q->where('subject_type', Interaction::class)->where('properties->status', false));
    }
}
