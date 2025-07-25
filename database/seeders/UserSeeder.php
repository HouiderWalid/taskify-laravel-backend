<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()
            ->credentials('houiderwalid@gmail.com', '123456789')
            ->giveRole(Role::ADMIN_ROLE)
            ->create();

        User::factory()->giveRole(Role::MEMBER_ROLE)->count(10)->create();
    }
}
