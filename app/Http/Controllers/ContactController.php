<?php

namespace App\Http\Controllers;

use App\Http\Requests\Contact\ContactRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function show(): View
    {
        return view('contact.show');
    }

    public function contact (ContactRequest $request): RedirectResponse {

        return redirect()->route('contact.show');

    }
}
