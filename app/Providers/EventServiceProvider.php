<?php

namespace App\Providers;

use App\Events\ExportFinished;
use App\Listeners\SendExportFinishedNotification;
use App\Listeners\UserEventSubscriber;
use App\Listeners\VideoEventSubscriber;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Interaction;
use App\Models\User;
use App\Models\Video;
use App\Observers\CategoryObserver;
use App\Observers\CommentObserver;
use App\Observers\InteractionObserver;
use App\Observers\UserObserver;
use App\Observers\VideoObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Login;
use App\Listeners\SendEmailVerificationNotification;
use App\Listeners\SuccessfulLogin;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;


class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        Login::class => [
            SuccessfulLogin::class,
        ],
        ExportFinished::class => [
            SendExportFinishedNotification::class,
        ]
    ];

    protected $subscribe = [
        UserEventSubscriber::class,
        VideoEventSubscriber::class,
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot() : void
    {
        Category::observe(CategoryObserver::class);
        Comment::observe(CommentObserver::class);
        User::observe(UserObserver::class);
        Video::observe(VideoObserver::class);
        Interaction::observe(InteractionObserver::class);
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents() : bool
    {
        return false;
    }
}
