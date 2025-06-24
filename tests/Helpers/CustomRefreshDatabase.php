<?php

namespace Tests\Helpers;

use Illuminate\Foundation\Testing\RefreshDatabase;

trait CustomRefreshDatabase
{
    use RefreshDatabase;

    /**
     * Migrate the database.
     *
     * @return void
     */
    protected function migrateDatabases(): void
    {
        $this->artisan('migrate:fresh');
        $this->artisan('migrate', ['--seed' => true, '--path' => 'database/migrations/foreign_keys']);
    }
}
