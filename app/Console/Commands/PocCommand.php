<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Traits\Localizable;

class PocCommand extends Command
{
    use Localizable;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'poc:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Proof of concept command (for testing only)';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle() : int
    {
        return self::SUCCESS;
    }
}
