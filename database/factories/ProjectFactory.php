<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $creator = User::whereHas(User::getRoleAttributeName(), function($query){
            $query->where(Role::getNameAttributeName(), Role::ADMIN_ROLE);
        })->first();

        if(!($creator instanceof User)) {
            throw new \Exception('No admin user found to assign as project creator');
        }
        
        return [
            Project::getNameAttributeName() => $this->faker->sentence(3),
            Project::getDescriptionAttributeName() => $this->faker->paragraph,
            Project::getCreatorIdAttributeName() => $creator->getId(),
            Project::getDueDateAttributeName() => $this->faker->dateTimeBetween('+1 day', '+1 month')
        ];
    }
}
