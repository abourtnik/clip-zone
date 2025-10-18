<?php

namespace App\Console\Commands\Patches;

use App\Jobs\GenerateThumbnail;
use App\Models\Video;
use Illuminate\Bus\Batch;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Bus;

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

    const array IDS = [211, 193, 191, 190, 189, 187, 164, 163, 160];

    public function handle() : int
    {
       $videos = Video::query()->whereIn('id', self::IDS)->get();

        foreach ($videos as $video) {

            $duration = (int) $video->getRawOriginal('duration');

            $timecodes = [1, intval(round($duration / 2)), intval($duration - 1)];

            $thumbnails = $video->thumbnails()->orderBy('id')->get();

            $this->info('Video id : ' .$video->id);
            $this->info(implode(',', $thumbnails->pluck('file')->toArray()));

            $thumbnailJobs = [];

            foreach ($timecodes as $index => $time) {
                $thumbnail = $thumbnails->get($index);
                $thumbnailJobs[] = new GenerateThumbnail($thumbnail, $time);
            }

            Bus::batch($thumbnailJobs)
                ->name("GenerateThumbnails for video : {$video->id}")
                ->then(function (Batch $batch) {
                    // Delete video
                    //Storage::disk('local')->delete(Video::VIDEO_FOLDER . DIRECTORY_SEPARATOR . $videoFile);
                })
                ->dispatch();
        }

        return Command::SUCCESS;
    }
}
