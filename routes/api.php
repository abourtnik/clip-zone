<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\CommentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth')->group(function () {
    Route::post("follow/{user}", [UserController::class, 'follow']);

    Route::post('like', [\App\Http\Controllers\LikeController::class, 'like']);
    Route::post('dislike', [\App\Http\Controllers\LikeController::class, 'dislike']);

    Route::resource("comments", CommentController::class)->only([
        'store', 'update', 'destroy'
    ]);
});

Route::get("comments/{video}", [VideoController::class, 'comments']);







