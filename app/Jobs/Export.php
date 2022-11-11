<?php

namespace App\Jobs;

use App\Models\User;
use App\Notifications\UserNotification;
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

    private string $model;
    private User $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $model, User $user)
    {
        $this->model = $model;
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $model = new $this->model();
        $table = $model->getTable();
        $class = 'App\Exports\\'.ucfirst($table).'Export';

        $this->user->notify(new UserNotification($table));

        Excel::store(new $class(), $table.'.csv', 'exports', BaseExcel::CSV);
    }
}
