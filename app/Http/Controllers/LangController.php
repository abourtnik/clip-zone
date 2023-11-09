<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class LangController extends Controller
{
    public function update(Request $request): RedirectResponse
    {
        if (array_key_exists($request->get('locale'), config('languages'))) {
            session()->put('applocale', $request->get('locale'));
        }

        return redirect()->back();
    }
}
