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
use App\Http\Controllers\VideoController;

// PAGES
Route::name('pages.')->controller(PageController::class)->group(function () {
    Route::get('/', 'home')->name('home');
    Route::get('/trend', 'trend')->name('trend');
    Route::get('/subscriptions', 'subscriptions')->name('subscriptions');
    Route::get('/search', 'search')->name('search');
    Route::get('/user/{user}', 'user')->name('user');
});

// VIDEOS
Route::controller(VideoController::class)->name('video.')->group(function () {
    Route::get('/video/{video}', 'show')->name('show');
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
    Route::resource('videos', VideoUserController::class);

    // Comments
    Route::controller(CommentUserController::class)->prefix('comments')->name('comments.')->group(function () {
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
