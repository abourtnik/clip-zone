<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Interaction;
use App\Models\Video;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Activity;

class ActivityController extends Controller
{
    public function index(Request $request) : View {

        $datesFilters = $request->only(['date_start', 'date_end']);

        return view('users.activity.index', [
            'user' => Auth::user()->loadCount([
                'activity as video_likes_count' => fn($query) => $query->filter($datesFilters)->whereHasMorph('subject', [Interaction::class], fn($query) => $query->whereHasMorph('likeable', [Video::class])->where('status', true)),
                'activity as comment_likes_count' => fn($query) => $query->filter($datesFilters)->whereHasMorph('subject', [Interaction::class], fn($query) => $query->whereHasMorph('likeable', [Comment::class])->where('status', true)),
                'activity as video_dislikes_count' => fn($query) => $query->filter($datesFilters)->whereHasMorph('subject', [Interaction::class], fn($query) => $query->whereHasMorph('likeable', [Video::class])->where('status', false)),
                'activity as comment_dislikes_count' => fn($query) => $query->filter($datesFilters)->whereHasMorph('subject', [Interaction::class], fn($query) => $query->whereHasMorph('likeable', [Comment::class])->where('status', false)),
                'activity as comments_count' => fn($query) => $query->filter($datesFilters)->whereHasMorph('subject', [Comment::class]),
            ]),
            'activities' => Activity::query()
                ->where('user_id', Auth::id())
                ->filter()
                ->with([
                    'subject' => function (MorphTo $morphTo) {
                        $morphTo->morphWith([
                            Comment::class => ['video'],
                            Interaction::class => [
                                'likeable' => function (MorphTo $morphTo) {
                                    $morphTo->morphWith([
                                        Video::class,
                                        Comment::class => ['video']
                                    ]);
                                }
                            ],
                        ]);
                    }
                ])
                ->latest('perform_at')
                ->paginate(12)
                ->withQueryString()
        ]);
    }
}
