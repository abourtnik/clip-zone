<?php

namespace App\Console\Commands\Patches;

use App\Models\Playlist;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Console\Command;

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
    public function handle(UserService $userService) : int
    {
        $users = User::query()
            ->whereNotNull('email_verified_at')
            ->whereNot('email', 'LIKE', '%@youtube.com%')
            ->whereDoesntHave('playlists', function ($query) {
                $query->where('title', Playlist::WATCH_LATER_PLAYLIST);
            })
            ->get();

        foreach ($users as $user) {
            $userService->createDefaultPlaylists($user);
        }

        return Command::SUCCESS;
    }
}
