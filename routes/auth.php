<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\OAuthController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\RegistrationController;
use App\Http\Controllers\Auth\EmailVerification;
use App\Http\Controllers\Auth\PhoneVerification;

// LOGIN
Route::controller(LoginController::class)->group(function () {
    Route::get('/login', 'show')->name('login')->middleware('guest');
    Route::post('/login', 'login')->name('login.perform')->middleware(['guest', 'throttle:login']);
    Route::get('/logout','logout')->name('logout');
});

// OAUTH
Route::controller(OAuthController::class)->name('oauth.')->prefix('oauth')->group(function () {
    Route::get('/connect/{service}', 'connect')
        ->name('connect')
        ->whereIn('service', array_keys(config('services')));
    Route::get('/callback/{service}', 'callback')
        ->name('callback')
        ->whereIn('service', array_keys(config('services')));
    Route::get('/unlink/{service}', 'unlink')
        ->name('unlink')
        ->middleware('auth')
        ->whereIn('service', array_keys(config('services')));
});

// REGISTER
Route::controller(RegistrationController::class)->middleware('guest')->group(function () {
    Route::get('/register', 'show')->name('registration');
    Route::post('/register', 'register')->name('registration.perform');
});

// PASSWORD RESET
Route::controller(PasswordController::class)->middleware('guest')->group(function () {
    Route::get('/forgot-password', 'forgot')->name('password.forgot');
    Route::post('/forgot-password', 'email')->name('password.email');
    Route::get('/reset-password/{token}', 'reset')->name('password.reset');
    Route::post('/reset-password', 'update')->name('password.update');
});

// EMAIL VERIFICATION
Route::controller(EmailVerification::class)
    ->middleware('auth')
    ->name('verification.')
    ->group(function () {
        Route::get('/email/verify', 'notice')->name('notice');
        Route::get('/email/verify/{id}/{hash}', 'verify')->name('verify')->middleware('signed');
        Route::post('/email/verification-notification', 'send')->name('send')->middleware('rate:1');
        Route::get('/email/verify-update/{id}/{hash}', 'verifyUpdate')->name('verify.update')->middleware('signed');
        Route::post('/email/verification-notification-update', 'sendUpdate')->name('send.update')->middleware('rate:1');
        Route::post('/email/verification-notification-update-cancel', 'cancelUpdate')->name('verify.update.cancel');
    });

// PHONE VERIFICATION
Route::controller(PhoneVerification::class)
    ->middleware('auth')
    ->name('phone.')
    ->prefix('phone')
    ->group(function () {
        Route::post('/verification-notification', 'send')->name('send')->middleware('rate:1');
        Route::post('/verify', 'verify')->name('verify');
    });
