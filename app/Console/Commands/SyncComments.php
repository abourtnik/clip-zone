<?php

namespace App\Console\Commands;

use App\Models\Comment;
use App\Models\User;
use App\Models\Video;
use App\Services\YoutubeService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class SyncComments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'comments:sync {id : ClipZone video id} {youtubeId : Youtube video id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get comments from Youtube for specific video';

    /**
     * Execute the console command.
     *
     * @param YoutubeService $youtubeService
     * @return int
     */
    public function handle(YoutubeService $youtubeService) : int
    {
        list('id' => $id, 'youtubeId' => $youtubeId) = $this->arguments();

        $video = Video::findOrFail($id);

        $video->comments->each->delete();

        $data = $youtubeService->getComments($youtubeId);

        $this->saveComments($data['items'], $video, null);

        return Command::SUCCESS;
    }

    private function saveComments (array $items, Video $video, ?Comment $parent) {

        $usedUsers = [];

        $count = count($items);

        $randomCount = rand($count - 5, $count);

        foreach ($items as $item) {

            $comment = $item['snippet']['topLevelComment']['snippet'] ?? $item['snippet'];

            $date = Carbon::create($comment['publishedAt']);

            $users = $this->getUsers([$video->user_id], $date)->diff($usedUsers);

            $userId = $users->random();

            $usedUsers[] = $userId;

            $savedComment = Comment::withoutEvents(function () use ($video, $comment, $userId, $date, $parent) {
                $data = [
                    'content' => $comment['textOriginal'],
                    'ip' => '37.67.157.29',
                    'user_id' => $userId,
                    'created_at' => $date,
                    'updated_at' => Carbon::create($comment['updatedAt']),
                ];

                if ($parent) {
                    $data['parent_id'] = $parent->id;
                }

                return $video->comments()->create($data);
            });

            // Add Comment interaction
            $this->generateInteraction($savedComment, $randomCount, $date);
            $randomCount = rand($randomCount - 5, $randomCount - 1);

            $replies = $item['replies']['comments'] ?? [];

            if($replies) {
                $this->saveComments($replies, $video, $savedComment);
            }
        }

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
