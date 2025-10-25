<?php

namespace App\Console\Commands\YoutubeSync;

use App\Models\User;
use App\Models\Video;
use App\Services\YoutubeService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class SyncVideosInteractions extends Command
{
    private YoutubeService $youtubeService;

    public function __construct(YoutubeService $youtubeService)
    {
        parent::__construct();
        $this->youtubeService = $youtubeService;
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'interactions:sync {video}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronize likes from Youtube';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle() : int
    {
        $videoId = $this->argument('video');

        $video = Video::query()
            ->whereNotNull('youtube_id')
            ->where('id', $videoId)
            ->first();

        $this->info('Sync interactions for video : ' .$video->title. ' ...');
        $video->interactions->each->delete();

        $data = $this->youtubeService->getVideo($video->youtube_id);

        $likeCount =  $data['items'][0]['statistics']['likeCount'] ?? null;

        if (is_null($likeCount)) {
            $this->info('Likes are not public : ' .$video->title);
            return Command::SUCCESS;
        }

        if ($likeCount > 1000) {
            $count = 1000;
        } else {
            $count = $likeCount;
        }

        $this->info($count. ' likes for : '. $video->title);

        $this->generateInteraction($video, $count);

        return Command::SUCCESS;
    }

    private function getUsers (Carbon $createdBefore): Collection {

        return User::query()
            ->active()
            ->where('created_at', '<', $createdBefore)
            ->where('email', 'LIKE', '%@youtube.com%')
            ->inRandomOrder()
            ->get()
            ->pluck('id');
    }

    private function generateInteraction(Video $video, int $count) : void {

        $interactionsCount = $count;

        $users = $this->getUsers($video->published_at);

        if ($users->count() < $count) {
            $interactionsCount = $users->count();
        }

        for ($i = 0; $i < $interactionsCount; $i++) {
            $video->interactions()->createQuietly([
                'user_id' => $users->get($i),
                'status' => fake()->boolean(95),
                'perform_at' => fake()->dateTimeBetween($video->published_at)
            ]);
        }
    }
}
