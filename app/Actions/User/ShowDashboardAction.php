<?php

namespace App\Actions\User;

use App\Models\Comment;
use App\Models\Interaction;
use App\Models\Pivots\Subscription;
use App\Models\Video;
use App\Models\View;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;

class ShowDashboardAction
{
    public function data(): array
    {
        return [
            'videos' => $this->getLastVideos(),
            'subscriptions' =>  $this->getLastSubscriptions(),
            'comments' => $this->getLastComments(),
            'interactions' => $this->getLastInteractions(),

            'videos_count'        => $this->countVideos(),
            'subscribers_count'   => $this->countSubscribers(),
            'videos_views_count'  => $this->countVideosViews(),
            'videos_comments_count' => $this->countVideosComments(),
            'videos_likes_count'  => $this->countVideoInteractions('likes'),
            'videos_dislikes_count' => $this->countVideoInteractions('dislikes'),

            'has_filter' => request()->has('date'),
        ];
    }

    private function getLastVideos(): Collection
    {
        return Video::query()
            ->where('user_id', Auth::user()->id)
            ->filter()
            ->withCount(['likes', 'dislikes', 'interactions', 'comments'])
            ->latest()
            ->limit(5)
            ->get();
    }

    private function getLastSubscriptions(): Collection
    {
        return Subscription::query()
            ->where('user_id', Auth::user()->id)
            ->filter()
            ->with(['subscriber' => fn($q) => $q->withCount('subscribers')])
            ->latest('subscribe_at')
            ->limit(5)
            ->get();
    }

    private function getLastComments(): Collection
    {
        return Comment::query()
            ->whereRelation('video', 'user_id', Auth::user()->id)
            ->filter()
            ->with(['user', 'video'])
            ->whereNull('parent_id')
            ->withCount('replies')
            ->latest()
            ->limit(5)
            ->get();
    }

    private function getLastInteractions(): Collection
    {
        return Interaction::query()
            ->whereMorphRelation('likeable',Video::class, 'user_id', Auth::user()->id)
            ->filter()
            ->with([
                'likeable' => function (MorphTo $morphTo) {
                    $morphTo->morphWith([
                        Video::class => ['user']
                    ]);
                },
                'user' => function ($query) {
                    $query->withTrashed();
                }
            ])
            ->latest('perform_at')
            ->limit(5)
            ->get();
    }

    private function countVideos(): int
    {
        return Video::query()
            ->where('user_id', Auth::user()->id)
            ->filter()
            ->count();
    }

    private function countSubscribers(): int
    {
        return Subscription::query()
            ->where('user_id', Auth::user()->id)
            ->filter()
            ->count();
    }

    private function countVideosViews(): int
    {
        return View::query()
            ->whereRelation('video', 'user_id', Auth::user()->id)
            ->filter()
            ->count();
    }

    private function countVideosComments(): int
    {
        return Comment::query()
            ->whereRelation('video', 'user_id', Auth::user()->id)
            ->filter()
            ->count();
    }

    private function countVideoInteractions(string $relation): int
    {
        return Interaction::query()
            ->whereHasMorph(
                'likeable',
                Video::class,
                fn($q) => $q->where('user_id', Auth::id())->whereHas($relation, fn($q) => $q->filter()))
            ->count();
    }
}
