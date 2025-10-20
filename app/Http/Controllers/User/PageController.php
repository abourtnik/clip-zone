<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Pivots\Subscription;
use App\Models\Video;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Auth;

class PageController extends Controller
{
    public function index() : View
    {
        return view('users.index', [
            'user' => Auth::user()->load([
                'videos' => function ($query) {
                    $query
                        ->filter()
                        ->withCount(['likes', 'dislikes', 'interactions', 'comments'])
                        ->orderBy('created_at', 'desc')
                        ->limit(5);
                },
                'subscribers' => function ($query) {
                    $query
                        ->filter()
                        ->withCount('subscribers')
                        ->orderBy('subscribe_at', 'desc')
                        ->limit(5);
                },
                "videos_comments" => function ($query) {
                    $query
                        ->filter()
                        ->with(['user', 'video'])
                        ->whereNull('parent_id')
                        ->withCount('replies')
                        ->orderBy('created_at', 'desc')
                        ->limit(5);
                },
                "videos_interactions" => function ($query) {
                    $query
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
                        ->orderBy('perform_at', 'desc')
                        ->limit(5);
                }
            ])->loadCount([
                'subscribers' => fn($query) => $query->filter(),
                'videos_views' => fn($query) => $query->filter(),
                'videos_interactions' => fn($query) => $query->whereHasMorph('likeable', Video::class, fn($query) => $query->whereHas('interactions', fn($query) => $query->filter())),
                'videos' => fn($query) => $query->filter(),
                'videos_comments' => fn($query) => $query->filter(),
                'videos_likes' => fn($query) => $query->whereHasMorph('likeable', Video::class, fn($query) => $query->whereHas('likes', fn($query) => $query->filter())),
                'videos_dislikes' => fn($query) => $query->whereHasMorph('likeable', Video::class, fn($query) => $query->whereHas('dislikes', fn($query) => $query->filter())),
            ])
        ]);
    }

    public function subscribers() : View {
        return view('users.subscribers', [
            'subscriptions' => Subscription::query()
                ->where('user_id', Auth::id())
                ->filter()
                ->with([
                    'subscriber' => function ($query) {
                        return $query
                            ->withCount('subscribers')
                            ->withExists([
                                'subscribers as is_current_user_subscribe' => fn($query) => $query->where('subscriber_id', Auth::id())
                            ]);
                    }
                ])
                ->orderBy('subscribe_at', 'desc')
                ->paginate(15)
                ->withQueryString()
        ]);
    }
}
