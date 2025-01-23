<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\RedirectResponse;

class NotificationController extends Controller
{
    public function click (Notification $notification) : RedirectResponse {

        $notification->markAsRead();

        return redirect($notification->url);
    }
}
