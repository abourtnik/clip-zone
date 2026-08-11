<?php

namespace App\Console\Commands;

use App\Models\Video;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
        $users = Storage::disk('local')->directories(Video::CHUNK_FOLDER);

        $now = now();

        $count = 0;

        foreach ($users as $user) {

            $chunks = Storage::disk('local')->directories($user);

            foreach ($chunks as $chunk) {

                $name = Str::afterLast($chunk, '/');

                $date = Carbon::createFromTimestampMs($name, config('app.timezone'));

                if ($date->addHour()->lt($now)) {
                    Storage::disk('local')->deleteDirectory($chunk);
                    $count ++;
                }
            }
        }

        $this->info(now()->format('Y-m-d H:i:s',).' - Delete ' .$count. ' chunks path');

        return Command::SUCCESS;
    }
}


