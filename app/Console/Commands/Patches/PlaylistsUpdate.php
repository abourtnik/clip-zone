<?php

namespace App\Console\Commands\Patches;

use App\Enums\PlaylistStatus;
use App\Models\User;
use App\Playlists\Types\LikedVideosPlaylist;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class PlaylistsUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'playlists:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update playlists table';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle() : int
    {
        $users = User::query()
            ->whereNotNull('email_verified_at')
            ->whereNot('email', 'LIKE', '%@youtube.com%')
            ->whereDoesntHave('playlists', function ($query) {
                $query->where('title', LikedVideosPlaylist::getName());
            })
            ->get();

        foreach ($users as $user) {
            $user->playlists()->create([
                'uuid' => Str::uuid()->toString(),
                'title' => LikedVideosPlaylist::getName(),
                'status' => PlaylistStatus::PRIVATE,
                'is_deletable' => false,
                'created_at' => $user->created_at
            ]);
        }

        return Command::SUCCESS;
    }
}
