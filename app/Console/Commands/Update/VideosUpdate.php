<?php

namespace App\Console\Commands\Update;

use App\Enums\ThumbnailStatus;
use App\Enums\VideoStatus;
use App\Helpers\VideoMetadata;
use App\Models\Video;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

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
    protected $description = 'Update videos table';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle() : int
    {
        $videos = Video::query()->withCount('views')->get();

        foreach ($videos as $video) {

            $views = $video->views_count;

            $video->update([
                'views' => $views
            ]);
        }

        return Command::SUCCESS;
    }
}
