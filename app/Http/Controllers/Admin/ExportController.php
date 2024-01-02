<?php

namespace App\Http\Controllers\Admin;

use App\Models\Export;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportController
{
    public function index () : View {
        return view('admin.exports.index', [
            'exports' => Export::latest()
                ->paginate(15)
                ->withQueryString()
        ]);
    }

    public function download (Export $export): StreamedResponse
    {
        return Storage::download($export->path);
    }

}
