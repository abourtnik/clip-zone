<?php

namespace App\Services;

use App\Models\Video;
use Illuminate\Database\Eloquent\Collection;

class VideoService {

    public function getSuggestedVideos($parentVideo): Collection
    {
        $user_videos = Video::whereNot('id', $parentVideo->id)
            ->where('user_id', $parentVideo->user->id)
            ->active()
            ->with(['user'])
            ->withCount(['views'])
            ->when($parentVideo->category, fn($q) => $q->orderByRaw("FIELD(category_id, ".$parentVideo->category->id.") DESC"))
            ->inRandomOrder()
            ->orderBy('views_count', 'desc')
            ->limit(3)
            ->get();

        if ($parentVideo->category) {
            $category_videos = Video::whereNotIn('id', [$parentVideo->id, ...$user_videos->pluck('id')])
                ->where('category_id', $parentVideo->category->id)
                ->active()
                ->with(['user'])
                ->withCount(['views'])
                ->inRandomOrder()
                ->orderBy('views_count', 'desc')
                ->limit(6 - $user_videos->count())
                ->get();
        } else {
            $category_videos = collect();
        }

        $random_videos = Video::whereNotIn('id', [$parentVideo->id, ...$user_videos->pluck('id'), ...$category_videos->pluck('id')])
            ->active()
            ->with(['user'])
            ->withCount(['views'])
            ->inRandomOrder()
            ->orderBy('views_count', 'desc')
            ->limit(9 - ($user_videos->count() + $category_videos->count()))
            ->get();

        return $user_videos->merge($category_videos)->merge($random_videos);
    }
}
