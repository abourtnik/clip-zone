<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\Profile\CodeRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PhoneVerification
{
    public function send(Request $request): RedirectResponse
    {
        $request->user()->sendPhoneVerificationNotification();

        return back()->with(['status' =>'Verification code sent successfully !']);
    }

    public function verify(CodeRequest $request): RedirectResponse
    {
        if ($request->get('code') === Auth::user()->getPhoneCodeVerification()) {
            $request->user()->markPhoneAsVerified();
            return back();
        }

        return back()->with(['error' => 'Invalid verification code !']);
    }
}
