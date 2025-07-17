<?php

namespace Database\Seeders;

use App\Classes\Helpers\UserPermission;
use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::factory()->count(count(UserPermission::ALL_PERMISSIONS))->create();
    }
}
