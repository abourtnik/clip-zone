<?php

namespace App\Console\Commands\Patches;

use App\Enums\ExportStatus;
use App\Models\Export;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ExportUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'exports:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update exports table';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle() : int
    {
       $exports = Export::query()
           ->whereNull('size')
           ->where('status', ExportStatus::COMPLETED)
           ->get();

       foreach ($exports as $export) {
           Export::withoutTimestamps(fn() => $export->update(['size' => Storage::size($export->path)]));
       }

        return Command::SUCCESS;
    }
}
