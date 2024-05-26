<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ExportController;
use App\Http\Controllers\Admin\CommentController as CommentAdminController;
use App\Http\Controllers\Admin\UserController as UserAdminController;
use App\Http\Controllers\Admin\VideoController as VideoAdminController;
use App\Http\Controllers\Admin\ReportController as ReportAdminController;
use App\Http\Controllers\Admin\CategoryController as CategoryAdminController;
use App\Http\Controllers\Admin\PlanController;
use App\Http\Controllers\Admin\InvoiceController as InvoiceAdminController;
use App\Http\Controllers\Admin\SpamController;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\OAuthController;
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
use App\Http\Controllers\PremiumController;

use App\Http\Controllers\StripeWebhookController;
use App\Http\Controllers\LangController;

use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\User\ActivityController;
use App\Http\Controllers\User\PlaylistController as PlaylistUserController;;
use App\Http\Controllers\User\CommentController as CommentUserController;
use App\Http\Controllers\User\SubtitleController;
use App\Http\Controllers\User\ReportController;
use App\Http\Controllers\User\NotificationController;
use App\Http\Controllers\User\InvoiceController;
use App\Http\Controllers\User\VideoController as VideoUserController;

use Laravel\Cashier\Http\Middleware\VerifyWebhookSignature;

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
    Route::get('/premium', 'premium')->name('premium');
});

// PREMIUM
Route::post('stripe-webhook', [StripeWebhookController::class, 'index'])
    ->middleware(VerifyWebhookSignature::class)
    ->name('stripe_webhook');

Route::name('premium.')->middleware(['auth', 'can:premiumSubscribe,App\Models\User'])->controller(PremiumController::class)->group(function () {
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
    Route::get('/{playlist}/favorite', 'favorite')->name('favorite');
    Route::get('/{playlist}/remove-favorite', 'removeFavorite')->name('remove-favorite');
    Route::get('/playlists/manage', 'manage')->name('manage')->middleware('auth');
});

// USERS
Route::controller(UserController::class)->name('user.')->group(function () {
    Route::get('/@{user:slug}', 'show')
        ->name('show')
        ->can('show', 'user');
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
    Route::get('/login', 'show')->name('login')->middleware('guest');
    Route::post('/login', 'login')->name('login.perform')->middleware('guest');
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
    Route::get('/register/verify/{id}/{token}', 'confirm')->name('registration.confirm');
});

// PASSWORD RESET
Route::controller(PasswordController::class)->middleware('guest')->group(function () {
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
    });

    Route::resource('videos', VideoUserController::class)->except([
        'create', 'store'
    ]);

    // Comments
    Route::resource('comments', CommentUserController::class)->only(['index', 'destroy']);

    // Subtitles
    Route::get('subtitles', [SubtitleController::class, 'list'])->name('subtitles.list');
    Route::resource('videos.subtitles', SubtitleController::class)->shallow();

    // Playlists
    Route::resource('playlists', PlaylistUserController::class)->except(['show']);

    // Activity
    Route::controller(ActivityController::class)->prefix('activity')->name('activity.')->group(function () {
        Route::get('/', 'index')->name('index');
    });

    // Reports
    Route::controller(ReportController::class)->prefix('report')->name('reports.')->group(function () {
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
        Route::get('/', 'index')->name('index');
        Route::get('{notification}/click', 'click')
            ->name('click')
            ->can('click', 'notification');
    });

    Route::get('/subscribers', [ProfileController::class, 'subscribers'])->name('subscribers');
});

// ADMIN
Route::prefix('admin')->middleware('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('/', [AdminController::class, 'index'])->name('index');

    Route::get('/download/{model}', [AdminController::class, 'download'])->name('download');

    // Users
    Route::controller(UserAdminController::class)->prefix('users')->name('users.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/{user}/ban', 'ban')
            ->name('ban')
            ->can('ban', 'user');
        Route::post('/{user}/confirm', 'confirm')->name('confirm');
        Route::get('/export', 'export')->name('export');
    });

    // Videos
    Route::controller(VideoAdminController::class)->prefix('videos')->name('videos.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/{video}/ban', 'ban')->name('ban');
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

    // Categories
    Route::controller(CategoryAdminController::class)->prefix('categories')->name('categories.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/organize', 'organize')->name('organize');
    });

    // Plans
    Route::controller(PlanController::class)->prefix('plan')->name('plans.')->group(function () {
        Route::get('/', 'index')->name('index');
    });

    // Invoices
    Route::controller(InvoiceAdminController::class)->prefix('invoice')->name('invoices.')->group(function () {
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
