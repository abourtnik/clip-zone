<?php

namespace App\Providers;

use App\Jobs\Export;
use App\Models\Notification;
use App\Models\Playlist;
use App\Models\Transaction;
use App\Models\Video;
use App\Models\Comment;
use App\Models\User;
use App\Policies\ExportPolicy;
use App\Policies\InvoicePolicy;
use App\Policies\NotificationPolicy;
use App\Policies\UserPolicy;
use App\Policies\VideoPolicy;
use App\Policies\CommentPolicy;
use App\Policies\PlaylistPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Video::class => VideoPolicy::class,
        Comment::class => CommentPolicy::class,
        User::class => UserPolicy::class,
        Playlist::class => PlaylistPolicy::class,
        Notification::class => NotificationPolicy::class,
        Export::class => ExportPolicy::class,
        Transaction::class => InvoicePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->registerPolicies();

        //
    }
}
