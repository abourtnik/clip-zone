<?php

namespace App\Http\Controllers;

use App\Http\Requests\LangRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class LangController extends Controller
{
    public function update(LangRequest $request): RedirectResponse
    {
        if (array_key_exists($request->get('locale'), config('languages'))) {
            session()->put('app_locale', $request->get('locale'));
        }

        if (Auth::check()) {
            Auth::user()->update(['language' => $request->get('locale')]);
        }

        return redirect()->back();
    }
}
