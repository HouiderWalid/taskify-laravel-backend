<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Console\Output\ConsoleOutput;

class TaskifyMigration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 't-migrate {--seed}';

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
        $output = new ConsoleOutput();
        Artisan::call('migrate', [], $output);
        Artisan::call('migrate', ['--path' => 'database/migrations/foreign_keys'], $output);

        $seed = $this->option('seed');
        if ($seed) {
            Artisan::call('db:seed', [], $output);
        }
    }
}
