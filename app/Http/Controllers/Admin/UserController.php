<?php

namespace App\Http\Controllers\Admin;

use App\Filters\UserFilters;
use App\Jobs\Export;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class UserController
{
    public function index (UserFilters $filters) : View {
        return view('admin.users.index', [
            'users' => User::filter($filters)
                ->withCount([
                    'subscribers',
                    'videos',
                    'comments',
                ])->whereNull('is_admin')
                ->orderBy('created_at', 'desc')
                ->paginate(15),
            'filters' => $filters->receivedFilters()
        ]);
    }

    public function ban (User $user) : RedirectResponse {

        $user->update([
            'banned_at' => now()
        ]);

        return redirect()->route('admin.users.index');
    }

    public function export (): RedirectResponse {
        Export::dispatch(User::class, Auth::user());
        return redirect()->route('admin.users.index')->withSuccess('Votre export a bien été pris en compte. Vous recevrez une <strong>notification</strong> quand celui-ci sera disponible.');
    }
}
