<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index() : View {
        return view('users.notifications', [
            'user_notifications' => Notification::query()
                ->where([
                    'notifiable_type' => User::class,
                    'notifiable_id' => Auth::id()
                ])
                ->latest()
                ->paginate(15)
        ]);
    }

    public function click (Notification $notification) : RedirectResponse {

        $notification->markAsRead();

        return redirect($notification->url);
    }

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
