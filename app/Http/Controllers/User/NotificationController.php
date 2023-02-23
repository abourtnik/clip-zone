<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
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

    public function read (Notification $notification) : RedirectResponse {

        $notification->update([
            'read_at' => now()
        ]);

        return redirect()->back();
    }

    public function unread (Notification $notification) : RedirectResponse {

        $notification->update([
            'read_at' => null
        ]);

        return redirect()->back();
    }

    public function remove (Notification $notification) : RedirectResponse {

        $notification->delete();

        return redirect()->back();
    }

    public function readAll () : RedirectResponse {

        Notification::where('user_id', Auth::user()->id)->update([
            'read_at' => now()
        ]);

        return redirect()->back();
    }
}
