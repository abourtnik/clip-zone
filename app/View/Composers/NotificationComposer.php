<?php

namespace App\View\Composers;

use App\Http\Resources\NotificationResource;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class NotificationComposer
{
    /**
     * Bind data to the view.
     *
     * @param View  $view
     * @return void
     */
    public function compose(View $view): void
    {
        $view->with('json_notifications', NotificationResource::collection(
            Notification::where([
                'notifiable_type' => User::class,
                'notifiable_id' => Auth::id()
            ])
            ->latest()
            ->limit(20)
            ->get()
        )->toJson());
    }
}
