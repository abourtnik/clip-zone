<?php

namespace App\Console\Commands\Patches;

use App\Models\Video;
use Illuminate\Console\Command;

class ViewsUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'views:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update views count';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle() : int
    {
        $videos = Video::query()->withCount('viewsHistory')->get();

        foreach ($videos as $video) {

            $views = $video->views_history_count;

            $video->update([
                'views' => $views
            ]);
        }

        return Command::SUCCESS;
    }
}
