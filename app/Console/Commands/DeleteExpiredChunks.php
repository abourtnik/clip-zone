<?php

namespace App\Console\Commands;

use App\Models\Video;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class DeleteExpiredChunks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'videos:delete_expired_chunks';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete expired chunks';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle() : int
    {
        $folders = Storage::disk('local')->directories(Video::CHUNK_FOLDER);

        $now = now();

        foreach ($folders as $folder) {

            $name = explode('/', $folder)[1];

            $date = Carbon::createFromTimestampMs($name);

            if ($date->addDays(1)->lt($now)) {
                Storage::disk('local')->deleteDirectory($folder);
            }
        }

        return Command::SUCCESS;
    }
}


