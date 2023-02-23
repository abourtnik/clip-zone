<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ArticleController;
use App\Http\Controllers\Admin\CommentController as CommentAdminController;
use App\Http\Controllers\Admin\UserController as UserAdminController;
use App\Http\Controllers\Admin\VideoController as VideoAdminController;
use App\Http\Controllers\Admin\ReportController as ReportAdminController;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\RegistrationController;

use App\Http\Controllers\VideoController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PlaylistController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\ContactController;

use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\User\ActivityController;
use App\Http\Controllers\User\PlaylistController as PlaylistUserController;;
use App\Http\Controllers\User\CommentController as CommentUserController;
use App\Http\Controllers\User\ReportController;
use App\Http\Controllers\User\NotificationController;
use App\Http\Controllers\User\VideoController as VideoUserController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// IMPERSONATE
Route::impersonate();

// PAGES
Route::name('pages.')->controller(PageController::class)->group(function () {
    Route::get('/', 'home')->name('home');
    Route::get('/trend', 'trend')->name('trend');
    Route::get('/categories/{slug}', 'category')->name('category');
    Route::get('/terms', 'terms')->name('terms');
    Route::middleware('auth')->group(function () {
        Route::get('/liked', 'liked')->name('liked');
    });
});

// VIDEOS
Route::controller(VideoController::class)->name('video.')->group(function () {
    Route::get('/video/{video:uuid}', 'show')
        ->name('show')
        ->can('show', 'video')
        ->missing(fn(Request $request) => abort(404, 'Video not found'));
    Route::get('/video/file/{video:uuid}', 'file')->name('file');
    Route::get('/video/download/{video:uuid}', 'download')->name('download')->can('download', 'video');
});

// USERS
Route::controller(PlaylistController::class)->name('playlist.')->group(function () {
    Route::get('/playlist/{playlist:uuid}', 'show')
        ->name('show')
        ->can('show', 'playlist')
        ->missing(fn(Request $request) => abort(404, 'Playlist not found'));
});

// PLAYLISTS
Route::controller(UserController::class)->name('user.')->group(function () {
    Route::get('/user/{user}', 'show')
        ->name('show')
        ->can('show', 'user')
        ->missing(fn(Request $request) => abort(404, 'User not found'));
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

// CONTACT
Route::controller(ContactController::class)->name('contact.')->group(function () {
    Route::get('/contact', 'show')->name('show');
    Route::post('/contact', 'contact')->name('contact');
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
    Route::get('/reset-password/{id}/{token}', 'reset')->name('password.reset');
    Route::post('/reset-password', 'update')->name('password.update');
});

// USER
Route::prefix('profile')->name('user.')->middleware(['auth'])->group(function () {

    // Profile
    Route::controller(ProfileController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/edit', 'edit')->name('edit');
        Route::put('/update', 'update')->name('update');
        Route::post('/update-password', 'updatePassword')->name('update-password');
        Route::delete('/delete', 'delete')->name('delete');
    });

    // Videos
    Route::controller(VideoUserController::class)->prefix('videos')->name('videos.')->group(function () {
        Route::post('/{video}/pin', 'pin')->name('pin');
        Route::post('/{video}/unpin', 'unpin')->name('unpin');
        Route::get('{video}/create', 'create')->name('create');
        Route::post('{video}/store', 'store')->name('store');
    });

    Route::resource('videos', VideoUserController::class)->except([
        'create', 'store'
    ]);

    // Comments
    Route::resource('comments', CommentUserController::class)->only(['index', 'destroy']);

    // Playlists
    Route::resource('playlists', PlaylistUserController::class)->except(['show']);

    // Activity
    Route::controller(ActivityController::class)->prefix('activity')->name('activity.')->group(function () {
        Route::get('/', 'index')->name('index');
    });

    // Reports
    Route::controller(ReportController::class)->prefix('report')->name('reports.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'report')->name('report');
        Route::post('/{report}/cancel', 'cancel')->name('cancel');
    });

    // Notifications
    Route::controller(NotificationController::class)->prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/{notification}/read', 'read')->name('read');
        Route::get('/{notification}/unread', 'unread')->name('unread');
        Route::get('/{notification}/remove', 'remove')->name('remove');
        Route::get('/read-all', 'readAll')->name('read-all');
    });

    Route::get('/subscribers', [ProfileController::class, 'subscribers'])->name('subscribers');
});

// ADMIN
Route::prefix('admin')->middleware('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('/', [AdminController::class, 'index'])->name('index');

    Route::get('/download/{model}', [AdminController::class, 'download'])->name('download');

    // Articles
    Route::controller(ArticleController::class)->prefix('articles')->name('articles.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/export', 'export')->name('export');
    });

    // Users
    Route::controller(UserAdminController::class)->prefix('users')->name('users.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/{user}/ban', 'ban')->name('ban');
        Route::get('/export', 'export')->name('export');
    });

    // Videos
    Route::controller(VideoAdminController::class)->prefix('videos')->name('videos.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/{video}/ban', 'ban')->name('ban');
        Route::get('/export', 'export')->name('export');
    });

    // Comments
    Route::controller(CommentAdminController::class)->prefix('comments')->name('comments.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/{comment}/ban', 'ban')->name('ban');
    });

    // Reports
    Route::controller(ReportAdminController::class)->prefix('reports')->name('reports.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/{report}/accept', 'accept')->name('accept');
        Route::post('/{report}/reject', 'reject')->name('reject');
    });
});
