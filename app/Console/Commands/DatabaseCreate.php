<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DatabaseCreate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new MySQL database based on the database config file';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle() : int
    {
        $name = config("database.connections.mysql.database");

        config(["database.connections.mysql.database" => null]);

        DB::statement("CREATE DATABASE IF NOT EXISTS $name CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");

        config(["database.connections.mysql.database" => $name]);

        return Command::SUCCESS;
    }
}
