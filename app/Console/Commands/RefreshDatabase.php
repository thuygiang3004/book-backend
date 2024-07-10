<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class RefreshDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:refresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run migrate:fresh and db:seed';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->call('migrate:fresh');
        $this->call('db:seed');
        return 0;
    }
}
