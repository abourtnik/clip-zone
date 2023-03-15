<?php

namespace App\Http\Controllers\User;

use App\Filters\ActivityFilters;
use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Interaction;
use App\Models\Video;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Auth;
use App\Models\Activity;

class ActivityController extends Controller
{
    public function index(ActivityFilters $filters) : View {
        return view('users.activity.index', [
            'user' => Auth::user()->loadCount([
                'activity as video_likes_count' => fn($query) => $query->filter($filters)->whereHasMorph('subject', [Interaction::class], fn($query) => $query->whereHasMorph('likeable', [Video::class])->where('status', true)),
                'activity as comment_likes_count' => fn($query) => $query->filter($filters)->whereHasMorph('subject', [Interaction::class], fn($query) => $query->whereHasMorph('likeable', [Comment::class])->where('status', true)),
                'activity as video_dislikes_count' => fn($query) => $query->filter($filters)->whereHasMorph('subject', [Interaction::class], fn($query) => $query->whereHasMorph('likeable', [Video::class])->where('status', false)),
                'activity as comment_dislikes_count' => fn($query) => $query->filter($filters)->whereHasMorph('subject', [Interaction::class], fn($query) => $query->whereHasMorph('likeable', [Comment::class])->where('status', false)),
                'activity as comments_count' => fn($query) => $query->filter($filters)->whereHasMorph('subject', [Comment::class]),
            ]),
            'activity_log' => Activity::causedBy(Auth::user())
                ->filter($filters)
                ->with([
                    'subject' => function (MorphTo $morphTo) {
                        $morphTo->morphWith([
                            Comment::class => ['video'],
                            Interaction::class => [
                                'likeable' => function (MorphTo $morphTo) {
                                    $morphTo->morphWith([
                                        Video::class,
                                    ]);
                                }
                            ],
                        ]);
                    }
                ])
                ->latest('updated_at')
                ->get()
                ->groupBy(fn ($item) => Carbon::parse($item->updated_at)->format('Y-m-d'))
                ->all(),
            'filters' => $filters->receivedFilters(),
            'types' => [
                'video_likes' => 'Video Likes',
                'comment_likes' => 'Comment Likes',
                'video_dislikes' => 'Video Dislikes',
                'comment_dislikes' => 'Comment Dislikes',
                'likes' => 'Likes',
                'dislikes' => 'Dislikes',
                'interactions' => 'Likes & Dislikes',
                'comments' => 'Comments'
            ]
        ]);
    }
}
