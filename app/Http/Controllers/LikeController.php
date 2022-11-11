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

        Auth::user()->like($likeable);

        return response()->json([
            'likes' => 1
        ]);
    }
}
