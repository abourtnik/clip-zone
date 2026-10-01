<?php

namespace App\Http\Controllers;

use App\Http\Requests\LangRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class LangController extends Controller
{
    public function update(LangRequest $request): RedirectResponse
    {
        $locale = $request->validated('locale');

        if (Auth::check()) {
            Auth::user()->update(['language' => $locale]);
        }

        return redirect()->back()->withCookie(cookie('app_locale', $locale, 60 * 24 * 365));
    }
}
