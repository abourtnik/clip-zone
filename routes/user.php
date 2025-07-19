<?php

use App\Http\Controllers\User\PageController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\User\ActivityController;
use App\Http\Controllers\User\PlaylistController;
use App\Http\Controllers\User\CommentController;
use App\Http\Controllers\User\SubtitleController;
use App\Http\Controllers\User\ReportController;
use App\Http\Controllers\User\NotificationController;
use App\Http\Controllers\User\InvoiceController;
use App\Http\Controllers\User\VideoController;

Route::prefix('profile')->name('user.')->middleware(['auth'])->group(function () {

    // Pages
    Route::controller(PageController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/subscribers', 'subscribers')->name('subscribers');
    });

    // Profile
    Route::controller(ProfileController::class)->group(function () {
        Route::get('/edit', 'edit')->name('edit');
        Route::put('/update', 'update')->name('update');
        Route::post('/update-password', 'updatePassword')->name('update.password')->middleware('password.confirm');;
        Route::delete('/delete', 'delete')->name('delete')->middleware('password.confirm');;
    });

    // Videos
    Route::controller(VideoController::class)->prefix('videos')->name('videos.')->group(function () {
        Route::post('{video}/pin', 'pin')
            ->name('pin')
            ->can('pin', 'video');
        Route::post('{video}/unpin', 'unpin')
            ->name('unpin')
            ->can('unpin', 'video');
        Route::get('{video}/create', 'create')
            ->name('create')
            ->can('update', 'video');
        Route::post('{video}/store', 'store')
            ->name('store')
            ->can('update', 'video');
        Route::delete('mass-delete', 'massDelete')
            ->name('mass-delete');
    });

    Route::resource('videos', VideoController::class)->except([
        'create', 'store'
    ]);

    // Comments
    Route::resource('comments', CommentController::class)->only(['index', 'destroy']);

    // Subtitles
    Route::get('subtitles', [SubtitleController::class, 'list'])->name('subtitles.list');
    Route::resource('videos.subtitles', SubtitleController::class)->shallow();

    // Playlists
    Route::resource('playlists', PlaylistController::class)->except(['show'])->middlewareFor(['create', 'store'], 'verified');
    Route::post('playlists/{playlist}/favorite', [PlaylistController::class, 'favorite'])->name('playlists.favorite')->can('favorite', 'playlist');

    // Activity
    Route::controller(ActivityController::class)->prefix('activity')->name('activity.')->group(function () {
        Route::get('/', 'index')->name('index');
    });

    // Reports
    Route::controller(ReportController::class)->prefix('reports')->name('reports.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/{report}/cancel', 'cancel')->name('cancel');
    });

    // Invoices
    Route::controller(InvoiceController::class)->prefix('invoice')->name('invoices.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/{transaction}', 'show')
            ->name('show')
            ->can('show', 'transaction');
    });

    // Notifications
    Route::controller(NotificationController::class)->prefix('notifications')->name('notifications.')->group(function () {
        Route::get('{notification}/click', 'click')
            ->name('click')
            ->can('click', 'notification');
    });
});



