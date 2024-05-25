<?php

namespace App\Console\Commands;

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
    protected $description = 'Add slug and regenerate shorter uuid for videos';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle() : int
    {
        $videos = Video::all();

        // Update env var APP_URL to http://nginx in local env
        // Be careful with non public videos
        // in production use temporaryUrl

        foreach ($videos as $video) {

            $this->info('Video : '. $video->id);

            if ($video->thumbnail) {
                $video->thumbnails()->create([
                    'file' => $video->thumbnail,
                    'is_active' => true,
                    'status' => ThumbnailStatus::UPLOADED
                ]);
            }

            $duration = $video->getRawOriginal('duration');

            $timecodes = [
                0,
                intval(round($duration / 2)),
                intval($duration - 1)
            ];

            foreach ($timecodes as $time) {

                $thumbnail = $video->thumbnails()->create();

                $previousStatus = $video->status;

                if ($previousStatus !== VideoStatus::PUBLIC) {
                    Video::withoutSyncingToSearch(function () use ($video) {
                        $video->update(['status' => VideoStatus::PUBLIC]);
                    });

                }

               $url = Storage::temporaryUrl(
                   Video::VIDEO_FOLDER .DIRECTORY_SEPARATOR .$video->file, now()->addSeconds(30)
               );

                $fileName = VideoMetadata::extractImages($url, $time);

                if ($fileName !== false) {
                    $thumbnail->update([
                        'file' => $fileName,
                        'is_active' => false,
                        'status' =>  ThumbnailStatus::GENERATED
                    ]);

                } else {
                    $thumbnail->update([
                        'status' =>  ThumbnailStatus::ERROR
                    ]);
                }

                if ($previousStatus !== VideoStatus::PUBLIC) {
                    Video::withoutSyncingToSearch(function () use ($video, $previousStatus) {
                        $video->update(['status' => $previousStatus]);
                    });

                }
            }
        }

        return Command::SUCCESS;
    }
}
