<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function read (Notification $notification) : Response {

        $notification->markAsRead();

        return response()->noContent();
    }

    public function unread (Notification $notification) : Response {

        $notification->markAsUnread();

        return response()->noContent();
    }

    public function delete (Notification $notification) : Response {

        $notification->delete();

        return response()->noContent();
    }

    public function readAll () : Response {

        Notification::where([
            'notifiable_type' => User::class,
            'notifiable_id' => Auth::id()
        ])->update([
            'read_at' => now()
        ]);

        return response()->noContent();
    }
}
