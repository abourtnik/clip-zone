<?php

namespace App\Console\Commands;

use App\Enums\VideoStatus;
use App\Events\VideoPublished;
use App\Models\Video;
use Illuminate\Console\Command;

class SendVideoPublishedEvent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'video:published';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send notification to subscribers when planned video become public for the first time';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle() : int
    {
        $videos = Video::where('status', VideoStatus::PLANNED)
            ->where('scheduled_date', now()->startOfMinute())
            ->where('publication_date', now()->startOfMinute())
            ->get();

        foreach ($videos as $video) {
            VideoPublished::dispatch($video);
        }

        return Command::SUCCESS;
    }
}
