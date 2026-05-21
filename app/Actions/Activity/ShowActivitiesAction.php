<?php

namespace App\Actions\Activity;


use App\Models\Activity;
use App\Models\Comment;
use App\Models\Interaction;
use App\Models\Video;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class ShowActivitiesAction
{
    public function data(): array
    {
       return [
           'activities' => $this->getActivities(),
           'count' => $this->buildCountsQuery(),
       ];
    }

    public function getActivities(): LengthAwarePaginator
    {
        return Activity::filter()
            ->where('user_id', Auth::id())
            ->with([
                'subject' => function (MorphTo $morphTo) {
                    $morphTo->morphWith([
                        Comment::class => ['video' => function (BelongsTo $query) {
                            $query->withTrashed();
                        }],
                        Interaction::class => [
                            'likeable' => function (MorphTo $morphTo) {
                                $morphTo->morphWith([
                                    Video::class,
                                    Comment::class => ['video' => function (BelongsTo $query) {
                                        $query->withTrashed();
                                    }],
                                ]);
                            }
                        ],
                    ]);
                }
            ])
            ->latest('perform_at')
            ->paginate(12)
            ->withQueryString();
    }

    private function buildCountsQuery(): Activity
    {
        return Activity::query()
            ->select(['activities.id', 'activities.perform_at'])
            ->filter()
            ->where('activities.user_id', Auth::id())
            ->leftJoin('interactions', function (JoinClause $join) {
                $join->on('activities.subject_id', '=', 'interactions.id')
                    ->where('activities.subject_type', '=', Interaction::class);
            })
            ->selectRaw('
                COUNT(CASE WHEN interactions.likeable_type = ? AND interactions.status = 1 THEN 1 END) as video_likes_count,
                COUNT(CASE WHEN interactions.likeable_type = ? AND interactions.status = 1 THEN 1 END) as comment_likes_count,
                COUNT(CASE WHEN interactions.likeable_type = ? AND interactions.status = 0 THEN 1 END) as video_dislikes_count,
                COUNT(CASE WHEN interactions.likeable_type = ? AND interactions.status = 0 THEN 1 END) as comment_dislikes_count,
                COUNT(CASE WHEN activities.subject_type = ? THEN 1 END) as comments_count
            ', [Video::class, Comment::class, Video::class, Comment::class, Comment::class])
            ->first();
    }
}
