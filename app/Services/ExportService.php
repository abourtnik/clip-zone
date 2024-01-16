<?php

namespace App\Services;

use App\Jobs\Export;
use App\Models\Export as ExportModel;
use Illuminate\Support\Facades\Auth;

class ExportService
{
    public function generate($type) :void
    {
        $export = new ExportModel();

        $export->save();

        Export::dispatch(Auth::user(), $type, $export);
    }
}
