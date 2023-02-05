<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\User\VideoController as VideoUserController;
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
    Route::post("follow/{user}", [UserController::class, 'follow'])->name('follow');

    // Interactions
    Route::name('interactions.')->controller(InteractionController::class)->group(function () {
        Route::get('/interactions', 'list')->name('list');
        Route::post('/like', 'like')->name('like');
        Route::post('/dislike', 'dislike')->name('dislike');
    });

    // Upload
    Route::post('/videos/upload', [VideoUserController::class, 'upload'])->name('videos.upload');

});

// SEARCH
Route::get("search", [SearchController::class, 'search'])->name('search');

// COMMENTS
Route::prefix('comments')->name('comments.')->controller(CommentController::class)->group(function () {
    Route::get('/', 'list')->name('list');
    Route::middleware('auth')->group(function () {
        Route::get('/{comment}/replies', 'replies')->name('replies');
        Route::post('/', 'store')->name('store');
        Route::put('/{comment}', 'update')->name('update')->can('update', 'comment');
        Route::delete('/{comment}', 'delete')->name('delete')->can('delete', 'comment');
        Route::post('/{comment}/pin', 'pin')->name('pin')->can('pin', 'comment');
        Route::post('/{comment}/unpin', 'unpin')->name('unpin')->can('pin', 'comment');
    });
});

// VIDEOS
Route::prefix('videos')->name('videos.')->controller(VideoController::class)->group(function () {
    Route::get('/home', 'home')->name('home');
    Route::get('/trend', 'trend')->name('trend');
    Route::get('/category/{category}', 'category')->name('category');
    Route::get('/user/{user}', 'user')->name('user');
});
