<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\InteractionController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\User\SearchController as SearchUserController;
use App\Http\Controllers\Admin\SearchController as SearchAdminController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\User\ReportController;
use App\Http\Controllers\User\NotificationController;
use App\Http\Controllers\Api\VideoController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\PlaylistController;
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
    Route::post('/videos/upload', [VideoController::class, 'upload'])
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
        Route::prefix('comments')->name('comments.')->controller(CommentController::class)->group(function () {
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

        // SEARCH USER
        Route::controller(SearchUserController::class)->prefix('search')->name('search.')->group(function () {
            Route::get('/users', 'users')->name('users');
            Route::post('/videos', 'videos')->name('videos');
        });

        // SEARCH ADMIN
        Route::controller(SearchAdminController::class)
            ->prefix('admin/search')
            ->name('admin.search.')
            ->middleware('admin')
            ->group(function () {
                Route::get('/users', 'users')->name('users');
                Route::get('/videos', 'videos')->name('videos');
            });
    });
});


/**
 * -------------------- PUBLIC --------------------
*/

Route::middleware('throttle:api')->group(function () {

    // AUTH
    Route::controller(LoginController::class)->group(function () {
        Route::post('/login', 'login');
        Route::get('/me', 'me')->name('me');
    });

    // SEARCH
    Route::get("search", [SearchController::class, 'search'])->name('search');

    // VIDEOS
    Route::prefix('videos')->name('videos.')->controller(VideoController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/trend', 'trend')->name('trend');
        Route::get('/{video:uuid}', 'show')
            ->can('show', 'video')
            ->can('delete', 'notification');;
    });

    // COMMENTS
    Route::prefix('comments')->name('comments.')->controller(CommentController::class)->group(function () {
        Route::get("/", [CommentController::class, 'list'])->name('comments.list');
        Route::get('/{comment}/replies', 'replies')->name('replies');
    });

    // USERS
    Route::prefix('users')->name('users.')->controller(UserController::class)->group(function () {
        Route::get('/{user:id}', 'show')->name('show');
        Route::get('/{user:id}/videos', 'videos')->name('videos');
        Route::get('/{user:id}/playlists', 'playlists')->name('playlists');
    });

    // PLAYLISTS
    Route::prefix('playlists')->name('playlists.')->controller(PlaylistController::class)->group(function () {
        Route::get('/{playlist:uuid}', 'show')->name('show');
    });

    // CATEGORIES
    Route::prefix('categories')->name('categories.')->controller(CategoryController::class)->group(function () {
        Route::get('/{category}/videos', 'videos')->name('videos');
    });
});
