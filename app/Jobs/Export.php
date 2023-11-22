<?php

namespace App\Jobs;

use App\Enums\ExportStatus;
use App\Events\ExportFinished;
use App\Models\User;
use App\Models\Export as ExportModel;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Excel as BaseExcel;

class Export implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public int $timeout = 120;

    public User $user;
    private string $export;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user, string $export)
    {
        $this->user = $user;
        $this->export = $export;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle() : void
    {
        $class = new $this->export();
        $fileName = $class->fileName.'-'.Carbon::now()->timestamp.'.csv';

        $export = \App\Models\Export::create([
            'file' => $fileName
        ]);

        Excel::store($class, ExportModel::EXPORT_FOLDER.'/'.$fileName, null, BaseExcel::CSV);

        $export->update(['status' => ExportStatus::COMPLETED]);

        ExportFinished::dispatch($this->user, $export);
    }
}
