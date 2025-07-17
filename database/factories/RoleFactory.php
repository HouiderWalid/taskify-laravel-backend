<?php

namespace Database\Factories;

use App\Classes\Helpers\UserPermission;
use App\Models\Permission;
use App\Models\Role;
use Exception;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Role>
 */
class RoleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Role::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            Role::getNameAttributeName() => $this->faker->unique()->randomElement(Role::getRoles())
        ];
    }

    public function configure(): RoleFactory|Factory
    {
        return $this->afterCreating(function (Role $role) {
            $role->defaultPermissions()->sync(
                Permission::whereIn(Permission::getNameAttributeName(), UserPermission::ROLE_DEFAULT_PERMISSIONS[$role->getName()])
                    ->pluck(Permission::getIdAttributeName())
            );
        });
    }

    /**
     * @param $name
     * @return RoleFactory
     * @throws Exception
     */
    public function name($name): RoleFactory
    {
        if (!in_array($name, Role::getRoles())) {
            throw new Exception('Role not valid.');
        }

        return $this->state(function (array $attributes) use ($name) {
            return [
                Role::getNameAttributeName() => $name,
            ];
        });
    }
}
