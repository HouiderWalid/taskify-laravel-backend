<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if (App::environment("local")) {
            $this->call([
                PermissionSeeder::class,
                RoleSeeder::class,
                UserSeeder::class,
                ProjectSeeder::class,
                TaskSeeder::class,
            ]);
            return;
        } else {
            $this->call([
                PermissionSeeder::class,
                RoleSeeder::class,
            ]);
        }
    }
}
