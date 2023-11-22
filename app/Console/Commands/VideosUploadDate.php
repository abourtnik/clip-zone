<?php

namespace App\Console\Commands;

use App\Models\Video;
use Illuminate\Console\Command;

class VideosUploadDate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'videos:upload_date';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fill videos uploaded_at in database';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle() : int
    {
        $videos = Video::all();

        foreach ($videos as $video) {
            $video->update([
                'uploaded_at' => $video->created_at
            ]);
        }

        return Command::SUCCESS;
    }
}
