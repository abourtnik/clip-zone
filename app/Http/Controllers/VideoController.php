<?php

namespace App\Http\Controllers;

use App\Http\Resources\InteractionsResource;
use App\Http\Resources\VideoResource;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Auth;

class VideoController
{
    public function load () : ResourceCollection {

        return VideoResource::collection(
            Video::active()
                ->with('user')
                ->withCount('views')
                ->latest('publication_date')
                ->paginate(24)
        );
    }

    public function show (Video $video, Request $request) : View {

        $limit = ['unit' => 'minutes', 'value' => 1];

        $ip_views_count = $video->views()->where('ip' , $request->ip())
            ->whereBetween('view_at', [now()->sub($limit['unit'], 1), now()])
            ->limit($limit['value'])
            ->count();

        if ($ip_views_count < $limit['value']) {
            $video->views()->create([
                'ip' => $request->ip(),
                'user_id' => Auth::id()
            ]);
        }

        return view('videos.show', [
            'video' => $video
                ->load([
                    'user' => fn($q) => $q->withCount('subscribers')
                ])
                ->loadCount([
                    'views',
                    'likes',
                    'dislikes',
                    'comments',
                    'likes as liked_by_auth_user' => fn($q) => $q->where('user_id', Auth::id()),
                    'dislikes as disliked_by_auth_user' => fn($q) => $q->where('user_id', Auth::id())
                ]),
            'videos' => Video::where('id', '!=', $video->id)
                ->with(['user'])
                ->withCount(['views'])
                ->active()
                ->inRandomOrder()
                ->limit(12)
                ->get()
        ]);
    }

    public function interactions(Video $video) {
        return InteractionsResource::collection(
            $video
                ->interactions()
                ->paginate(10)
        );
    }
}
