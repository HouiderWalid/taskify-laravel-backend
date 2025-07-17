<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Console\Output\ConsoleOutput;

class TaskifyRefreshMigration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 't-migrate:refresh {--seed}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $seed = $this->option('seed');
        $output = new ConsoleOutput();
        Artisan::call('t-migrate:rollback', [], $output);
        Artisan::call('t-migrate' . ($seed ? ' --seed' : ''), [], $output);
    }
}
