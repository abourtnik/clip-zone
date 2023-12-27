<?php

namespace App\Console\Commands;

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
    protected $signature = 'interactions:sync {from?} {to?}';

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
        list('from' => $from, 'to' => $to) = $this->arguments();

        $videos = Video::query()
            ->whereNotNull('youtube_id')
            ->when($from, fn($query) => $query->where('id', '>=' , $from))
            ->when($to, fn($query) => $query->where('id', '<=' , $to))
            ->get();

        foreach ($videos as $video) {

            // $this->info('Sync interactions for video : ' .$video->title. ' ...');
            //$video->interactions->each->delete();

            $data = $this->youtubeService->getVideo($video->youtube_id);

            $likeCount =  $data['items'][0]['statistics']['likeCount'] ?? null;

            if (is_null($likeCount)) {
                $this->info('Likes are not public : ' .$video->title);
                continue;
            }

            $count = round($likeCount / 8000);

            $this->info($count. ' likes for : '. $video->title);

            $this->generateInteraction($video, $count);
        }

        return Command::SUCCESS;
    }

    private function getUsers (array $excludeIds, Carbon $createdBefore): Collection {

        return User::query()
            ->active()
            ->when($excludeIds, fn($q) => $q->whereNotIn('id', $excludeIds))
            ->where('created_at', '<', $createdBefore)
            ->whereNotIn('id', [6, 7, 9, 10, 12, 14, 15, 16, 19, 20, 21, 23])
            ->inRandomOrder()
            //->limit(5)
            ->get()
            ->pluck('id');
    }

    private function generateInteraction(Video $video, int $count) {

        $users = $this->getUsers([], $video->publication_date);

        if ($users->count() < $count) {
            $this->error('Not enough users');
        }

        return;

        for ($i = 0; $i < $count; $i++) {

            $id = $users->get($i);

            /*
            $video->interactions()->create([
                'user_id' => $userId,
                'status' => fake()->boolean(95),
                'perform_at' => fake()->dateTimeBetween($afterDate)
            ]);
            */
        }
    }
}
