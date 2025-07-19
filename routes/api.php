<?php

use App\Http\Controllers\Api\Admin\SearchController as SearchAdminController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\Private\DeviceController;
use App\Http\Controllers\Api\Private\InteractionController;
use App\Http\Controllers\Api\Private\NotificationController;
use App\Http\Controllers\Api\Private\PlaylistController;
use App\Http\Controllers\Api\Private\ReportController;
use App\Http\Controllers\Api\Private\SearchController;
use App\Http\Controllers\Api\Private\ThumbnailController;
use App\Http\Controllers\Api\Private\UserController;
use App\Http\Controllers\Api\Private\VideoController;
use App\Http\Controllers\Api\Private\SubscribeController;
use App\Http\Controllers\Api\Public\CategoryController;
use App\Http\Controllers\Api\Public\PlaylistController as PublicPlaylistController;
use App\Http\Controllers\Api\Public\SearchController as SearchPublicController;
use App\Http\Controllers\Api\Public\UserController as PublicUserController;
use App\Http\Controllers\Api\Public\VideoController as PublicVideoController;
use App\Models\Video;
use Illuminate\Support\Facades\Route;


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
 * -------------------- PRIVATE USER --------------------
 */

Route::middleware('auth:sanctum')->group(function () {

    // SUBSCRIBE
    Route::post("subscribe/{user}", [SubscribeController::class, 'subscribe'])
        ->name('subscribe')
        ->can('subscribe', 'user')
        ->middleware(['throttle:subscribe', 'verified'])
        ->missing(fn() => abort(404, 'User not found'));

    // VIDEOS
    Route::controller(VideoController::class)
        ->prefix('videos')
        ->name('videos.')
        ->middleware('verified')
        ->group(function () {
            Route::post('/upload', 'upload')
                ->name('upload')
                ->middleware('throttle:upload')
                ->can('upload', Video::class);
            Route::delete('/{video:uuid}', 'delete')->name('delete')->can('delete', 'video');
    });

    Route::middleware('throttle:api')->group(function () {

        // INTERACTIONS
        Route::name('interactions.')
            ->controller(InteractionController::class)
            ->middleware('verified')
            ->group(function () {
                Route::get('/videos/{video:id}/interactions', 'list')->name('list')->can('interactions', 'video');
                Route::post('/like', 'like')->name('like');
                Route::post('/dislike', 'dislike')->name('dislike');
        });

        // REPORT
        Route::post('/report', [ReportController::class, 'report'])->name('report')->middleware('verified');

        // PLAYLIST
        Route::controller(PlaylistController::class)->prefix('playlists')->name('playlists.')->group(function () {
            Route::post('/', 'store')->name('store')->middleware('verified');
            Route::post('/save', 'save')->name('save');
            Route::delete('/{playlist:uuid}', 'delete')->name('delete')->can('delete', 'playlist');
        });

        // NOTIFICATIONS
        Route::controller(NotificationController::class)->prefix('notifications')->name('notifications.')->group(function () {
            Route::get('/', 'index')
                ->name('index');
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

        // SEARCH
        Route::controller(SearchController::class)->prefix('search')->name('search.')->group(function () {
            Route::get('/users', 'users')->name('users');
            Route::get('/videos', 'videos')->name('videos');
        });

        // CURRENT USER (for MOBILE)
        Route::prefix('me')
            ->name('me.')
            ->controller(UserController::class)
            ->group(function () {
                Route::get('/', 'user')->name('user');
                Route::get('/videos', 'videos')->name('videos');
                Route::get('/playlists', 'playlists')->name('playlists');
                Route::get('/subscriptions-videos', 'subscriptionsVideos')->name('subscriptions-videos');
                Route::get('/subscriptions-channels', 'subscriptionsChannels')->name('subscriptions-channels');
                Route::post('/logout', 'logout')->name('logout');
        });

        // DEVICES
        Route::prefix('devices')
            ->name('devices.')
            ->controller(DeviceController::class)
            ->group(function () {
                Route::post('/{device}/update', 'update')->name('update');
                Route::delete('/{device}', 'delete')->name('delete');
        });

        // THUMBNAILS
        Route::name('thumbnails.')
            ->controller(ThumbnailController::class)
            ->group(function () {
                Route::get('/videos/{video:id}/thumbnails', 'index')
                    ->name('list')
                    ->can('thumbnails', 'video');
            });
    });
});


/**
 * -------------------- PUBLIC --------------------
*/

Route::middleware('throttle:api')->group(function () {

    // AUTH
    Route::controller(LoginController::class)->group(function () {
        Route::post('/login', 'login')->middleware(['throttle:login']);
    });

    // SEARCH
    Route::get("search", [SearchPublicController::class, 'search'])->name('search');

    // VIDEOS
    Route::prefix('videos')->name('videos.')->controller(PublicVideoController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/trend', 'trend')->name('trend');
        Route::get('/{video:uuid}', 'show')->can('show', 'video');
    });

    // USERS
    Route::prefix('users')
        ->name('users.')
        ->controller(PublicUserController::class)
        ->middleware('can:show,user')
        ->group(function () {
            Route::get('/{user:id}', 'show')->name('show');
            Route::get('/{user:id}/videos', 'videos')->name('videos');
            Route::get('/{user:id}/playlists', 'playlists')->name('playlists');
    });

    // PLAYLISTS
    Route::prefix('playlists')
        ->name('playlists.')
        ->controller(PublicPlaylistController::class)
        ->middleware('can:show,playlist')
        ->group(function () {
            Route::get('/{playlist:uuid}', 'show')->name('show');
            Route::get('/{playlist:uuid}/videos', 'videos')->name('videos');
    });

    // CATEGORIES
    Route::prefix('categories')->name('categories.')->controller(CategoryController::class)->group(function () {
        Route::get('/{category}/videos', 'videos')->name('videos');
    });
});


// COMMENTS
Route::prefix('videos/{video:uuid}/comments')->name('comments.')
    ->controller(CommentController::class)
    ->middleware('throttle:api')
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/{comment}/replies', 'replies')->name('replies');
        Route::middleware('auth:sanctum')->group(function () {
            Route::post('/', 'store')->name('store')->middleware('verified');
            Route::put('/{comment}', 'update')->name('update');
            Route::delete('/{comment}', 'delete')->name('delete');
            Route::post('/{comment}/pin', 'pin')->name('pin');
            Route::post('/{comment}/unpin', 'unpin')->name('unpin');
        });
});

/**
 * -------------------- ADMIN --------------------
 */

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth:sanctum', 'admin'])
    ->group(function () {

        // SEARCH
        Route::controller(SearchAdminController::class)
            ->name('search.')
            ->group(function () {
                Route::get('/users', 'users')->name('users');
                Route::get('/videos', 'videos')->name('videos');
            });
});
