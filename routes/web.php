<?php

use App\Http\Controllers\VideoController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\User\CustomPlaylistController;
use App\Http\Controllers\PlaylistController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\PremiumController;

use App\Http\Controllers\StripeWebhookController;
use App\Http\Controllers\LangController;

use App\Enums\CustomPlaylistType;

use Laravel\Cashier\Http\Middleware\VerifyWebhookSignature;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// PAGES
Route::name('pages.')->controller(PageController::class)->group(function () {
    Route::get('/', 'home')->name('home');
    Route::get('/trend', 'trend')->name('trend');
    Route::get('/categories/{slug}', 'category')->name('category');
    Route::get('/terms', 'terms')->name('terms');
    Route::middleware('auth')->group(function () {
        Route::get('/history', 'history')->name('history');
    });
    Route::get('/premium', 'premium')->name('premium');
});

// CUSTOM PLAYLISTS
Route::middleware('auth')->name('custom-playlist.')->controller(CustomPlaylistController::class)->group(function () {
    Route::get('/playlist/{type}', 'show')
        ->whereIn('type', CustomPlaylistType::cases())
        ->name('show');
    Route::post('/watch-later/clear', 'clearWatchedVideos')->name('clear-watched-videos');
});

// PREMIUM
Route::post('stripe-webhook', [StripeWebhookController::class, 'index'])
    ->middleware(VerifyWebhookSignature::class)
    ->name('stripe_webhook');

Route::name('premium.')->middleware(['auth', 'can:premiumSubscribe,App\Models\User', 'verified'])->controller(PremiumController::class)->group(function () {
    Route::get('/subscribe/{plan}', 'subscribe')
        ->name('subscribe');
});

// LANG
Route::post('update-locale', [LangController::class, 'update'])->name('lang.update');

// VIDEOS
Route::controller(VideoController::class)->name('video.')->prefix('video')->group(function () {
    Route::get('{slug}-{video:uuid}', 'show')
        ->name('show')
        ->can('show', 'video')
        ->where('slug', '[a-z0-9A-Z\-]+');
    Route::get('file/{video:file}', 'file')
        ->name('file')
        ->can('file', 'video');
    Route::get('{video:uuid}/thumbnail', 'thumbnail')
        ->name('thumbnail')
        ->can('thumbnail', 'video');
    Route::get('{video:uuid}/download', 'download')
        ->name('download')
        ->can('download', 'video');
    Route::get('{video:uuid}/subtitles/{subtitle}', 'subtitles')
        ->scopeBindings()
        ->name('subtitles')
        ->can('show', 'subtitle');
    Route::get('{video:uuid}/thumbnails/{thumbnail}', 'thumbnails')
        ->scopeBindings()
        ->name('thumbnails')
        ->can('thumbnails', 'video');
    Route::get('{video:uuid}/embed', 'embed')
        ->name('embed')
        ->missing(fn (Request $request) => response()->view('videos.embed.missing'));
});

// SHORTS
/*
Route::controller(\App\Http\Controllers\ShortController::class)->name('short.')->group(function () {
    Route::get('/shorts', 'index')
        ->name('index');
    Route::get('/shorts/{video:uuid}', 'show')
        ->name('show')
        ->can('show', 'video');
});
*/


// PLAYLISTS
Route::controller(PlaylistController::class)->name('playlist.')->group(function () {
    Route::get('/playlist/{playlist:uuid}', 'show')
        ->name('show')
        ->can('show', 'playlist');
    Route::get('/playlists/manage', 'manage')->name('manage')->middleware('auth');
});

// USERS
Route::controller(UserController::class)->name('user.')->group(function () {
    Route::get('/@{user:slug}', 'show')
        ->name('show')
        ->can('show', 'user');
    Route::prefix('users')->group(function () {
        Route::get('{user}/avatar', 'avatar')
            ->name('avatar')
            ->can('avatar', 'user');
        Route::get('{user}/banner', 'banner')
            ->name('banner')
            ->can('banner', 'user');
    });
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

// CONTACT
Route::controller(ContactController::class)->name('contact.')->group(function () {
    Route::get('/contact', 'show')->name('show');
    Route::post('/contact', 'contact')->name('contact')->middleware('throttle:contact');
});

require __DIR__.'/auth.php';
require __DIR__.'/user.php';
require __DIR__.'/admin.php';
