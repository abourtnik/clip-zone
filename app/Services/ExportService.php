<?php

namespace App\Services;

use App\Enums\ExportStatus;
use App\Jobs\Export;
use App\Models\Export as ExportModel;
use Illuminate\Support\Facades\Auth;

class ExportService
{
    public function generate(string $type, array $filters = []): void
    {
        $export = ExportModel::create([
            'status' => ExportStatus::PENDING
        ]);

        Export::dispatch(Auth::user(), $type, $export, $filters);
    }
}
