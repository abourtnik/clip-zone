<?php

namespace App\Http\Controllers;

use App\Http\Resources\InteractionsResource;
use App\Models\Activity;
use App\Models\Video;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Auth;

class InteractionController extends Controller
{
    public function like(Request $request): JsonResponse
    {
        $model = $request->get('model');

        $likeable = $model::findOrFail($request->get('id'));

        if (Auth::user()->hasLiked($likeable)) {
            $a = $likeable->likes()
                ->whereHas('user', fn($q) => $q->whereId(Auth::user()->id))
                ->first();

            Activity::forSubject($a)->delete();
            $a->delete();

        } else {
            Auth::user()->interactions()->create([
                'likeable_type' => get_class($likeable),
                'likeable_id' => $likeable->id,
                'status' => true,
            ]);
        }
        if (Auth::user()->hasDisliked($likeable)) {
            $a = $likeable->dislikes()
                ->whereHas('user', fn($q) => $q->whereId(Auth::user()->id))
                ->first();

            Activity::forSubject($a)->delete();
            $a->delete();
        }

        return response()->json([

        ]);
    }

    public function dislike(Request $request): JsonResponse
    {
        $model = $request->get('model');

        $likeable = $model::findOrFail($request->get('id'));

        if (Auth::user()->hasDisliked($likeable)) {
            $a = $likeable->dislikes()
                ->whereHas('user', fn($q) => $q->whereId(Auth::user()->id))
                ->first();

            Activity::forSubject($a)->delete();
            $a->delete();


        } else {
            Auth::user()->interactions()->create([
                'likeable_type' => get_class($likeable),
                'likeable_id' => $likeable->id,
                'status' => false,
            ]);
        }

        if (Auth::user()->hasLiked($likeable)) {
            $a = $likeable->likes()
                ->whereHas('user', fn($q) => $q->whereId(Auth::user()->id))
                ->first();

            Activity::forSubject($a)->delete();
            $a->delete();

        }

        return response()->json([

        ]);
    }

    public function list (Request $request) : ResourceCollection {

        $filter = $request->get('filter', 'all');
        $search = $request->get('search');
        $video_id = $request->get('video_id');

        $video = Video::findOrFail($video_id)->loadCount('comments');

        return InteractionsResource::collection(
            $video->interactions()
                ->when($filter === 'up', fn($query) => $query->where('status', true))
                ->when($filter === 'down', fn($query) => $query->where('status', false))
                ->when($search, fn($query) => $query->whereRelation('user', 'username', 'LIKE',  '%'.$search.'%'))
                ->latest('perform_at')
                ->paginate(40)
                ->withQueryString()
        );
    }
}
