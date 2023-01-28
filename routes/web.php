<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegistrationController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ArticleController;
use App\Http\Controllers\Admin\UserController as UserAdminController;
use App\Http\Controllers\Admin\VideoController as VideoAdminController;
use App\Http\Controllers\User\VideoController as VideoUserController;
use App\Http\Controllers\User\CommentController as CommentUserController;
use App\Http\Controllers\User\ActivityController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\HistoryController;

// PAGES
Route::name('pages.')->controller(PageController::class)->group(function () {
    Route::get('/', 'home')->name('home');
    Route::get('/trend', 'trend')->name('trend');
    Route::get('/categories/{slug}', 'category')->name('category');
    Route::get('/user/{user}', 'user')->name('user')->can('show', 'user');
    Route::get('/terms', 'terms')->name('terms');

    Route::middleware('auth')->group(function () {
        Route::get('/liked', 'liked')->name('liked');
    });
});

// VIDEOS
Route::controller(VideoController::class)->name('video.')->group(function () {
    Route::get('/video/{video:uuid}', 'show')->name('show')->can('show', 'video');
    Route::get('/video/file/{video:uuid}', 'file')->name('file');
    Route::get('/video/download/{video:uuid}', 'download')->name('download')->can('download', 'video');
});

// SEARCH
Route::controller(SearchController::class)->name('search.')->group(function () {
    Route::get('/search', 'index')->name('index');
});

// SUBSCRIPTION
Route::controller(SubscriptionController::class)->name('subscription.')->group(function () {
    Route::get('/subscriptions', 'index')->name('index');
    Route::get('/manage', 'manage')->name('manage')->middleware('auth');
    Route::get('/discover', 'discover')->name('discover');

});

// HISTORY
Route::controller(HistoryController::class)->name('history.')->middleware('auth')->group(function () {
    Route::get('/history', 'index')->name('index');
    Route::get('/history-clear', 'clear')->name('clear');
});

// LOGIN
Route::controller(LoginController::class)->group(function () {
    Route::get('/login', 'show')->name('login');
    Route::post('/login', 'login')->name('login.perform');
    Route::get('/logout','logout')->name('logout');
});

// REGISTER
Route::controller(RegistrationController::class)->group(function () {
    Route::get('/register', 'show')->name('registration');
    Route::post('/register', 'register')->name('registration.perform');
    Route::get('/register/verify/{id}/{token}', 'confirm')->name('registration.confirm');
});

// PASSWORD RESET
Route::controller(PasswordController::class)->group(function () {
    Route::get('/forgot-password', 'forgot')->name('password.forgot');
    Route::post('/forgot-password', 'email')->name('password.email');
    Route::get('/reset-password/{token}', 'reset')->name('password.reset');
    Route::post('/reset-password', 'update')->name('password.update');
});

// USER
Route::prefix('profile')->name('user.')->middleware(['auth'])->group(function () {

    // Profile
    Route::controller(UserController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/edit', 'edit')->name('edit');
        Route::put('/update', 'update')->name('update');
        Route::post('/update-password', 'updatePassword')->name('update-password');
        Route::delete('/delete', 'delete')->name('delete');
    });

    // Videos
    Route::post('/{video}/pin', [VideoUserController::class, 'pin'])->name('videos.pin');
    Route::post('/{video}/unpin', [VideoUserController::class, 'unpin'])->name('videos.unpin');
    Route::resource('videos', VideoUserController::class);

    // Comments
    Route::controller(CommentUserController::class)->prefix('comments')->name('comments.')->group(function () {
        Route::get('/', 'index')->name('index');
    });

    // Activity
    Route::controller(ActivityController::class)->prefix('activity')->name('activity.')->group(function () {
        Route::get('/', 'index')->name('index');
    });

    Route::get('/subscribers', [UserController::class, 'subscribers'])->name('subscribers');
});

// ADMIN
Route::prefix('admin')->middleware('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('index');
    Route::get('/download/{model}', [AdminController::class, 'download'])->name('download');
    Route::controller(ArticleController::class)->prefix('articles')->name('articles.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/export', 'export')->name('export');
    });
    Route::controller(UserAdminController::class)->prefix('users')->name('users.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/export', 'export')->name('export');
    });
    Route::controller(VideoAdminController::class)->prefix('videos')->name('videos.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/export', 'export')->name('export');
    });
});
