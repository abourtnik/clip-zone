<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Storage;

use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminController
{
    public function index(): View {
        return view('admin.index');
    }

    public function download(string $model): StreamedResponse
    {
        return Storage::download('exports/'.$model.'.csv');
    }
}
