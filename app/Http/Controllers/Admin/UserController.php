<?php

namespace App\Http\Controllers\Admin;

use App\Jobs\Export;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class UserController
{
    public function index () : View {
        return view('admin.users.index', [
            'users' => User::all()
        ]);
    }

    public function export (): RedirectResponse {
        Export::dispatch(User::class, Auth::user());
        return redirect()->route('admin.users.index')->withSuccess('Votre export a bien été pris en compte. Vous recevrez une <strong>notification</strong> quand celui-ci sera disponible.');
    }
}
