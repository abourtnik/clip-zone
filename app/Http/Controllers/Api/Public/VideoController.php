<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Resources\Video\VideoListResource;
use App\Http\Resources\Video\VideoShowResource;
use App\Models\Video;
use App\Services\ViewService;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class VideoController
{
    public function index(): ResourceCollection
    {
        return VideoListResource::collection(
            Video::active()
                ->with('user')
                ->latest('published_at')
                ->cursorPaginate(24)
        );
    }

    public function trend(): ResourceCollection
    {
        return VideoListResource::collection(
            Video::active()
                ->with('user')
                ->orderBy('views', 'desc')
                ->cursorPaginate(24)
        );
    }

    public function show(Video $video): VideoShowResource
    {
        return new VideoShowResource(
            $video
                ->load([
                    'user' => function ($q) {
                        return $q
                            ->withCount('subscribers')
                            ->withExists([
                                'subscribers as subscribed_by_auth_user' => fn($q) => $q->where('subscriber_id', Auth::id()),
                            ]);
                    },
                    'reportByAuthUser'
                ])
                ->loadCount(['comments', 'likes', 'dislikes'])
                ->loadExists([
                    'likes as liked_by_auth_user' => fn($q) => $q->where('user_id', Auth::id()),
                    'dislikes as disliked_by_auth_user' => fn($q) => $q->where('user_id', Auth::id()),
                    'reports as reported_by_auth_user' => fn($q) => $q->where('user_id', Auth::id()),
                ])
        );
    }

    public function view(Video $video, ViewService $viewService): Response
    {
        $viewService->incrementView($video);

        return response()->noContent();
    }
}
