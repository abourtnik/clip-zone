<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ExportController;
use App\Http\Controllers\Admin\CommentController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\VideoController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\PlanController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\SpamController;

Route::prefix('admin')->name('admin.')->middleware(['admin'])->group(function () {

    // Dashboard
    Route::get('/', [AdminController::class, 'index'])->name('index');

    Route::get('/download/{model}', [AdminController::class, 'download'])->name('download');

    // Users
    Route::controller(UserController::class)->prefix('users')->name('users.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/{user}/ban', 'ban')
            ->name('ban')
            ->can('ban', 'user');
        Route::post('/{user}/confirm', 'confirm')->name('confirm');
        Route::post('/{user}/premium', 'premium')->name('premium');
        Route::delete('/{user}/delete', 'delete')->name('delete');
        Route::get('/export', 'export')->name('export');
    });

    // Videos
    Route::controller(VideoController::class)->prefix('videos')->name('videos.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/{video}/ban', 'ban')->name('ban');
    });

    // Comments
    Route::controller(CommentController::class)->prefix('comments')->name('comments.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/{comment}/ban', 'ban')->name('ban');
    });

    // Reports
    Route::controller(ReportController::class)->prefix('reports')->name('reports.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/{report}/accept', 'accept')->name('accept');
        Route::post('/{report}/reject', 'reject')->name('reject');
    });

    // Categories
    Route::controller(CategoryController::class)->prefix('categories')->name('categories.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/organize', 'organize')->name('organize');
    });

    // Plans
    Route::controller(PlanController::class)->prefix('plan')->name('plans.')->group(function () {
        Route::get('/', 'index')->name('index');
    });

    // Invoices
    Route::controller(InvoiceController::class)->prefix('invoice')->name('invoices.')->group(function () {
        Route::get('/', 'index')->name('index');
    });

    // Exports
    Route::controller(ExportController::class)->prefix('exports')->name('exports.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/{export}/download', 'download')
            ->name('download')
            ->can('download', 'export');
    });

    // Spam
    Route::controller(SpamController::class)->prefix('spam')->name('spams.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::put('/update', 'update')->name('update');
    });

    // Logs
    Route::get('/logs', fn() => redirect('logs'))->name('logs');

    // Telescope
    Route::get('/telescope', fn() => redirect('telescope'))->name('telescope');

    // Horizon
    Route::get('/horizon', fn() => redirect('horizon'))->name('horizon');

});


