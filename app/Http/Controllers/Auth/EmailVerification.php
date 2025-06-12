<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\Auth\EmailVerificationUpdateRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

class EmailVerification
{
    public function notice(): View
    {
        return view('auth.verify-email');
    }

    public function verify(EmailVerificationRequest $request): RedirectResponse
    {
        $request->fulfill();

        return redirect()->route('pages.home');
    }

    public function send(Request $request)
    {
        $request->user()->sendEmailVerificationNotification();

        return back()->with(['status' =>'Verification link sent successfully !']);
    }

    public function verifyUpdate(EmailVerificationUpdateRequest $request)
    {
        $request->fulfill();

        return redirect()->route('user.edit');
    }

    public function sendUpdate(Request $request)
    {
        $request->user()->sendUpdatedEmailVerificationNotification();

        return back()->with(['status' =>'Verification link sent successfully !']);
    }

    public function cancelUpdate(Request $request)
    {
        $request->user()->update(['temporary_email' => null]);

        return redirect()->route('user.edit');
    }
}
