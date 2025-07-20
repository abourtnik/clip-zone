<?php

namespace App\Console\Commands\YoutubeSync;

use App\Models\Comment;
use App\Models\User;
use App\Models\Video;
use App\Services\YoutubeService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SyncComments extends Command
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
    protected $signature = 'comments:sync {from?} {to?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronize comments from Youtube';

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
            ->where('allow_comments', true)
            ->when($from, fn($query) => $query->where('id', '>=' , $from))
            ->when($to, fn($query) => $query->where('id', '<=' , $to))
            ->get();

        foreach ($videos as $video) {

            $this->info('Sync comments for video : ' .$video->title. ' ...');

            $video->comments->each->delete();

            $data = $this->youtubeService->getComments($video->youtube_id);

            $this->saveComments($data['items'], $video, null);
        }

        return Command::SUCCESS;
    }

    private function saveComments (array $items, Video $video, ?Comment $parent) {

        $count = count($items);

        $randomCount = rand($count - 5, $count);

        foreach ($items as $item) {

            $comment = $item['snippet']['topLevelComment']['snippet'] ?? $item['snippet'];

            $date = Carbon::create($comment['publishedAt']);

            // Author of video is author of comment
            if ($comment['channelId'] === $comment['authorChannelId']['value']) {
                $userId = $video->user_id;
            } else {
                $user = $this->importUser($comment['authorChannelId']['value']);
                $userId = $user->id;
            }

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
            $randomCount = $randomCount - rand(1, 5);

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
            ->get()
            ->pluck('id');
    }

    private function generateInteraction(Comment $comment, int $count, Carbon $afterDate) {

        $usedUsers = [];

        for ($i = $count; $i > 0; $i--) {

            $users = $this->getUsers([], $afterDate)->diff($usedUsers);

            $userId = $users->random();

            $usedUsers[] = $userId;

            $comment->interactions()->createQuietly([
                'user_id' => $userId,
                'status' => fake()->boolean(96),
                'perform_at' => fake()->dateTimeBetween($afterDate)
            ]);
        }
    }

    private function importUser (string $youtubeChannelId) : User {

        $this->info($youtubeChannelId);

        $data = $this->youtubeService->getChannelInfo($youtubeChannelId);

        $channel = $data['items'][0]['snippet'];

        $user = User::where('username' , $channel['title'])->first();

        if ($user) {
            $user->update([
                'description' => $channel['description'] ?: null,
                'slug' => Str::remove('@', $channel['customUrl']),
                'created_at' => Carbon::create($channel['publishedAt']),
                'country' => $channel['country'] ?? null,
            ]);

            return $user;

        } else {

            // Save avatar

            try {
                $contentAvatar = file_get_contents($channel['thumbnails']['medium']['url']);
            }
            catch (\Exception $e){
                $this->info('error get avatar : '. $e->getMessage());
            }

            if ($contentAvatar ?? null) {
                $avatarName = Str::random(40) . '.jpg';
                Storage::put(User::AVATAR_FOLDER . '/' . $avatarName, $contentAvatar);
            }

            // Save Banner
            if ($data['items'][0]['brandingSettings']['image']['bannerExternalUrl'] ?? null) {

                $url = $data['items'][0]['brandingSettings']['image']['bannerExternalUrl'] . '=w2120-fcrop64=1,00005a57ffffa5a8-k-c0xffffffff-no-nd-rj';

                try {
                    $contentBanner = file_get_contents($url);
                }
                catch (\Exception $e){
                    $this->info('error get banner : '. $e->getMessage());
                }

                if ($contentBanner ?? null) {
                    $bannerName = Str::random(40) . '.jpg';
                    Storage::put(User::BANNER_FOLDER . '/' .$bannerName, $contentBanner);
                }
            }

            return User::create([
                'username' => $channel['title'],
                'slug' => Str::remove('@', $channel['customUrl']),
                'email' => Str::slug($channel['title']) .'-'. Carbon::now()->timestamp .'@youtube.com',
                'password' => Str::random(),
                'email_verified_at' => Carbon::create($channel['publishedAt'])->addSeconds(rand(10, 300)),
                'avatar' => $avatarName ?? null,
                'banner' => $bannerName ?? null,
                'description' => $channel['description'] ?: null,
                'country' => $channel['country'] ?? null,
                'show_subscribers' => true,
                'created_at' => Carbon::create($channel['publishedAt']),
            ]);
        }
    }
}
