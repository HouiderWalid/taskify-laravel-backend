<?php

namespace Database\Factories;

use App\Classes\Helpers\UserPermission;
use App\Models\Permission;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Permission>
 */
class PermissionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            Permission::getNameAttributeName() => $this->faker->unique()->randomElement(UserPermission::ALL_PERMISSIONS)
        ];
    }
}
