<?php

namespace App\View\Composers;

use App\Http\Resources\NotificationResource;
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
            Auth::user()->notifications()->latest()->limit(20)->get()
        )->toJson());
    }
}
