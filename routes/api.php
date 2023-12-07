<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\InteractionController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\User\ReportController;
use App\Http\Controllers\User\NotificationController;
use App\Http\Controllers\User\PlaylistController;
use App\Http\Controllers\User\VideoController as VideoUserController;
use App\Http\Controllers\VideoController;
use Illuminate\Support\Facades\Route;
use App\Models\Video;

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


/**
 * -------------------- PRIVATE --------------------
 */

Route::middleware('auth:sanctum')->group(function () {

    // SUBSCRIBE
    Route::post("subscribe/{user}", [ProfileController::class, 'subscribe'])
        ->name('subscribe')
        ->can('subscribe', 'user')
        ->middleware('throttle:subscribe')
        ->missing(fn() => abort(404, 'User not found'));

    // UPLOAD
    Route::post('/videos/upload', [VideoUserController::class, 'upload'])
        ->name('videos.upload')
        ->middleware('throttle:upload')
        ->can('upload', Video::class);

    Route::middleware('throttle:api')->group(function () {

        // INTERACTIONS
        Route::name('interactions.')->controller(InteractionController::class)->group(function () {
            Route::get('/interactions', 'list')->name('list');
            Route::post('/like', 'like')->name('like');
            Route::post('/dislike', 'dislike')->name('dislike');
        });

        // COMMENTS
        Route::prefix('comments')->name('comments.')
            ->controller(CommentController::class)->group(function () {
                Route::get('/{comment}/replies', 'replies')->name('replies');
                Route::post('/', 'store')->name('store');
                Route::put('/{comment}', 'update')->name('update')->can('update', 'comment');
                Route::delete('/{comment}', 'delete')->name('delete')->can('delete', 'comment');
                Route::post('/{comment}/pin', 'pin')->name('pin')->can('pin', 'comment');
                Route::post('/{comment}/unpin', 'unpin')->name('unpin')->can('pin', 'comment');
            });

        // REPORT
        Route::post('/report', [ReportController::class, 'report'])->name('report');

        // PLAYLIST
        Route::controller(PlaylistController::class)->prefix('playlists')->name('playlists.')->group(function () {
            Route::get('/{video}', 'list')->name('list');
            Route::post('/save', 'save')->name('save');
        });

        // NOTIFICATIONS
        Route::controller(NotificationController::class)->prefix('notifications')->name('notifications.')->group(function () {
            Route::get('/{notification}/read', 'read')
                ->name('read')
                ->missing(fn() => abort(404, 'Notification not found'))
                ->can('read', 'notification');
            Route::get('/{notification}/unread', 'unread')->name('unread')
                ->missing(fn() => abort(404, 'Notification not found'))
                ->can('unread', 'notification');
            Route::delete('/{notification}/delete', 'delete')
                ->name('remove')
                ->missing(fn() => abort(404, 'Notification not found'))
                ->can('delete', 'notification');
            Route::get('/read-all', 'readAll')->name('read-all');
        });
    });

});


/**
 * -------------------- PUBLIC --------------------
*/

Route::middleware('throttle:api')->group(function () {

    // SEARCH
    Route::get("search", [SearchController::class, 'search'])->name('search');
    Route::post("search-videos", [SearchController::class, 'searchVideos'])->name('search-videos');

// VIDEOS
    Route::prefix('videos')->name('videos.')->controller(VideoController::class)->group(function () {
        Route::get('/home', 'home')->name('home');
        Route::get('/trend', 'trend')->name('trend');
        Route::get('/category/{category}', 'category')->name('category');
        Route::get('/user/{user}', 'user')->name('user');
    });

// COMMENTS
    Route::get("comments", [CommentController::class, 'list'])->name('comments.list');

});
