<?php

namespace App\Console\Commands;

use App\Models\Video;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class VideosSize extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'videos:size';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fill videos size in database';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle() : int
    {
        $files = Storage::disk('videos')->files();

        foreach ($files as  $file) {
            Video::where('file', $file)->update([
                'size' => Storage::disk('videos')->size($file)
            ]);
        }

        return Command::SUCCESS;
    }
}
