<?php

use App\Http\Controllers\Webhooks\StripeWebhookController;
use App\Http\Controllers\Webhooks\TranscriptionWebhookController;
use App\Http\Middleware\VerifyTranscriptWebhook;
use Laravel\Cashier\Http\Middleware\VerifyWebhookSignature;

Route::middleware(['throttle:60,1'])
    ->prefix('webhook')
    ->name('webhook.')
    ->group(function () {
        Route::post('stripe', [StripeWebhookController::class, 'index'])
            ->middleware(VerifyWebhookSignature::class)
            ->name('stripe');
        Route::post('transcript', [TranscriptionWebhookController::class, 'index'])
            ->middleware(VerifyTranscriptWebhook::class)
            ->name('transcript');
});
