<?php

namespace App\Jobs;

use App\Enums\ExportStatus;
use App\Events\Export\ExportFail;
use App\Events\Export\ExportFinished;
use App\Models\Export as ExportModel;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Maatwebsite\Excel\Excel as BaseExcel;
use Maatwebsite\Excel\Facades\Excel;
use Throwable;

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
    private string $exportType;
    private ExportModel $export;
    private array $filters;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user, string $exportType, ExportModel $export, array $filters)
    {
        $this->user = $user;
        $this->exportType = $exportType;
        $this->export = $export;
        $this->filters = $filters;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle() : void
    {
        $class = new $this->exportType($this->filters);
        $fileName = $class->fileName.'-'.Carbon::now()->timestamp.'.csv';

        Excel::store($class, ExportModel::EXPORT_FOLDER.'/'.$fileName, null, BaseExcel::CSV);

        $this->export->update([
            'file' => $fileName,
            'status' => ExportStatus::COMPLETED
        ]);

        ExportFinished::dispatch($this->user, $this->export);
    }

    /**
     * Handle a job failure.
     *
     * @param Throwable  $exception
     * @return void
     */
    public function failed(Throwable $exception) : void
    {
        $this->export->update(['status' => ExportStatus::ERROR]);
        ExportFail::dispatch($this->user, $this->export);
    }
}
