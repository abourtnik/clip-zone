<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ArticleController;
use App\Http\Controllers\Admin\UserController as UserAdminController;
use App\Http\Controllers\User\VideoController;

// Pages
Route::name('pages.')->controller(PageController::class)->group(function () {
    Route::get('/', 'home')->name('home');
    Route::get('/trend', 'trend')->name('trend');
    Route::get('/video/{video}', 'video')->name('video');
    Route::get('/user/{user}', 'user')->name('user');
});

// Login
Route::controller(LoginController::class)->group(function () {
    Route::get('/login', 'show')->name('login');
    Route::post('/login', 'login')->name('login.perform');
    Route::get('/logout',)->name('logout');
});

// Register
Route::controller(RegisterController::class)->group(function () {
    Route::get('/register', 'show')->name('register');
    Route::post('/register', 'register')->name('register.perform');
});

// User
Route::name('user.')->middleware('auth')->group(function () {
    Route::get('/account', [UserController::class, 'index'])->name('index');
    Route::get('/profile', [UserController::class, 'profile'])->name('profile');
    Route::put('/update', [UserController::class, 'update'])->name('update');
    Route::resource('videos', VideoController::class);
});

// Admin
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
});
