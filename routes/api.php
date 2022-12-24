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

Route::middleware('auth')->group(function () {
    Route::post("follow/{user}", [UserController::class, 'follow']);

    Route::post('like', [\App\Http\Controllers\LikeController::class, 'like']);
    Route::post('dislike', [\App\Http\Controllers\LikeController::class, 'dislike']);

    Route::get('/comments/{comment}/replies', [CommentController::class, 'replies']);

    Route::resource("comments", CommentController::class)->only([
        'index', 'store', 'update', 'destroy'
    ]);

    Route::get("interactions/{video}", [VideoController::class, 'interactions']);

});

Route::get("search", [\App\Http\Controllers\PageController::class, 'searchResult']);
Route::get("videos", [VideoController::class, 'load']);







