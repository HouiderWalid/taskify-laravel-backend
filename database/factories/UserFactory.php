<?php

namespace Database\Factories;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        $role = Role::query()->where(Role::getNameAttributeName(), Role::MEMBER_ROLE)->first() ?? Role::factory()->create();

        return [
            User::getFullNameAttributeName() => $this->faker->text(10),
            User::getEmailAttributeName() => $this->faker->unique()->email(),
            User::getRoleIdAttributeName() => $role instanceof Role ? $role->getId() : null,
            User::getPasswordAttributeName() => Hash::make($this->faker->password)
        ];
    }

    public function configure(): Factory|UserFactory
    {
        return $this->afterCreating(function (User $user) {
            $user->permissions()->sync($user->getDefaultPermissions());
        });
    }

    public function credentials($email, $password): UserFactory
    {
        return $this->state(function (array $attributes) use ($email, $password) {
            return [
                User::getEmailAttributeName() => $email,
                User::getPasswordAttributeName() => Hash::make($password),
            ];
        });
    }

    public function giveRole(string $roleName): UserFactory
    {
        return $this->state(function (array $attributes) use ($roleName) {

            $role = Role::where(Role::getNameAttributeName(), $roleName)->first();

            if (!($role instanceof Role)) {
                throw new Exception("Role {$roleName} not found");
            }

            return [
                User::getRoleIdAttributeName() => $role->getId(),
            ];
        });
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
            User::getEmailVerifiedAtAttributeName() => null,
        ]);
    }
}
