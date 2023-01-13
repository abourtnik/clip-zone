<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\InteractionController;

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

    Route::get('/comments/{comment}/replies', [CommentController::class, 'replies']);

    Route::resource("comments", CommentController::class)->only([
        'store', 'update', 'destroy'
    ]);

    // Interactions
    Route::name('interactions.')->controller(InteractionController::class)->group(function () {
        Route::get('/interactions', 'list')->name('list');
        Route::post('/like', 'like')->name('like');
        Route::post('/dislike', 'dislike')->name('dislike');
    });

});

Route::get("comments", [CommentController::class, 'list']);
Route::get("search", [SearchController::class, 'search']);

// VIDEOS
Route::prefix('videos')->name('videos.')->controller(VideoController::class)->group(function () {
    Route::get('/home', 'home')->name('home');
    Route::get('/trend', 'trend')->name('trend');
    Route::get('/category/{category}', 'category')->name('category');
});






