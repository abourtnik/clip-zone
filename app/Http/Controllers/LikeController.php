<?php

namespace App\Http\Controllers;

use App\Models\Interfaces\Likeable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function like(Request $request): JsonResponse
    {
        $model = $request->get('model');

        $likeable = $model::findOrFail($request->get('id'));

        if (Auth::user()->hasLiked($likeable)) {
            $likeable->likes()
                ->whereHas('user', fn($q) => $q->whereId(Auth::user()->id))
                ->delete();
        } else {
            Auth::user()->likes()->create([
                'likeable_type' => get_class($likeable),
                'likeable_id' => $likeable->id,
                'status' => true,
            ]);
        }

        if (Auth::user()->hasDisliked($likeable)) {
            $likeable->dislikes()
                ->whereHas('user', fn($q) => $q->whereId(Auth::user()->id))
                ->delete();
        }

        return response()->json([

        ]);
    }

    public function dislike(Request $request): JsonResponse
    {
        $model = $request->get('model');

        $likeable = $model::findOrFail($request->get('id'));

        if (Auth::user()->hasDisliked($likeable)) {
            $likeable->dislikes()
                ->whereHas('user', fn($q) => $q->whereId(Auth::user()->id))
                ->delete();
        } else {
            Auth::user()->likes()->create([
                'likeable_type' => get_class($likeable),
                'likeable_id' => $likeable->id,
                'status' => false,
            ]);
        }

        if (Auth::user()->hasLiked($likeable)) {
            $likeable->likes()
                ->whereHas('user', fn($q) => $q->whereId(Auth::user()->id))
                ->delete();
        }

        return response()->json([

        ]);
    }
}
