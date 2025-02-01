<?php

namespace App\Http\Controllers\Admin;

use App\Services\SpamService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SpamController
{
    public function index(SpamService $spamService): View {
        return view('admin.spams.index', [
            'words' => $spamService->getWordsAsString(),
            'emails' => $spamService->getEmailsAsString()
        ]);
    }

    public function update(SpamService $spamService, Request $request): RedirectResponse {

        $data = $request->only('words', 'emails');

        $spamService->update($data);

        return redirect()->route('admin.spams.index')
            ->with('success', "Updated successfully");
    }
}
