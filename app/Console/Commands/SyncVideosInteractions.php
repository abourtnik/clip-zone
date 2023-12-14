<?php

namespace App\Console\Commands;

use App\Models\Comment;
use App\Models\User;
use App\Models\Video;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class SyncVideosInteractions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'interactions:sync {id : ClipZone video id} {count : Interaction number}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate interactions for specific video';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle() : int
    {
        list('id' => $id, 'count' => $count) = $this->arguments();

        $video = Video::findOrFail($id);

        $video->interactions()->delete();

        $usedUsers = [];

        for ($i = $count; $i > 0; $i--) {

            $users = $this->getUsers([], $video->publication_date)->diff($usedUsers);

            $userId = $users->random();

            $usedUsers[] = $userId;

            $video->interactions()->create([
                'user_id' => $userId,
                'status' => fake()->boolean(93),
                'perform_at' => fake()->dateTimeBetween($video->publication_date)
            ]);
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

    private function generateInteraction(Comment $comment, int $count, Carbon $afterDate) {

        $usedUsers = [];

        for ($i = $count; $i > 0; $i--) {

            $users = $this->getUsers([], $afterDate)->diff($usedUsers);

            $userId = $users->random();

            $usedUsers[] = $userId;

            $comment->interactions()->create([
                'user_id' => $userId,
                'status' => fake()->boolean(93),
                'perform_at' => fake()->dateTimeBetween($afterDate)
            ]);
        }
    }
}
