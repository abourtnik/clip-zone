<?php

namespace App\Console\Commands\Youtube;

use App\Integrations\YoutubeClient;
use App\Models\User;
use App\Models\Video;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class SyncVideosInteractions extends Command
{
    private YoutubeClient $youtubeClient;

    public function __construct(YoutubeClient $youtubeClient)
    {
        parent::__construct();
        $this->youtubeClient = $youtubeClient;
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'youtube:interactions-sync {video}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronize interactions from Youtube';

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

        $data = $this->youtubeClient->getVideo($video->youtube_id);

        $likeCount =  $data['items'][0]['statistics']['likeCount'] ?? null;

        if (is_null($likeCount)) {
            $this->info('Likes are not public : ' .$video->title);
            return self::SUCCESS;
        }

        $count = min($likeCount, 1000);

        $this->info($count. ' likes for : '. $video->title);

        $this->generateInteraction($video, $count);

        return self::SUCCESS;
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
