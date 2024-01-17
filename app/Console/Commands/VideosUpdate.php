<?php

namespace App\Console\Commands;

use App\Helpers\Number;
use App\Models\Video;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class VideosUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'videos:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add slug and regenerate shorter uuid for videos';

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
               'slug' => Str::slug($video->title),
               'uuid' => Number::unique()
            ]);
        }

        return Command::SUCCESS;
    }
}
