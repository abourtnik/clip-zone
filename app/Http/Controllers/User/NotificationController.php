<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index() : View {
        return view('users.notifications', [
            'user_notifications' => Notification::where('user_id', Auth::user()->id)
                ->latest()
                ->paginate(15)
        ]);
    }

    public function read (Notification $notification) : Response {
        $notification->update([
            'read_at' => now()
        ]);

        return response()->noContent();
    }

    public function unread (Notification $notification) : Response {

        $notification->update([
            'read_at' => null
        ]);

        return response()->noContent();
    }

    public function delete (Notification $notification) : Response {

        $notification->delete();

        return response()->noContent();
    }

    public function readAll () : Response {

        Notification::where('user_id', Auth::user()->id)->update([
            'read_at' => now()
        ]);

        return response()->noContent();
    }
}
