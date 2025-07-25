<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\Role;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $project = Project::inRandomOrder()->first() ?? Project::factory()->create();
        $assignedToUser = User::whereHas(User::getRoleAttributeName(), function($query){
            $query->where(Role::getNameAttributeName(), Role::MEMBER_ROLE);
        })->inRandomOrder()->first() ?? User::factory()->giveRole(Role::MEMBER_ROLE)->create();

        if (!($project instanceof Project)) {
            throw new \Exception('No project found to assign task to');
        }

        if (!($assignedToUser instanceof User)) {
            throw new \Exception('No user found to assign task to');
        }

        return [
            Task::getProjectIdAttributeName() => $project->getId(),
            Task::getAssignedToUserIdAttributeName() => $assignedToUser->getId(),
            Task::getTitleAttributeName() => $this->faker->sentence(3),
            Task::getDescriptionAttributeName() => $this->faker->paragraph,
            Task::getDueDateAttributeName() => $this->faker->dateTimeBetween('+1 month', '+1 month'),
            Task::getPriorityAttributeName() => $this->faker->randomElement(Task::getPriorityNames()),
            Task::getStatusAttributeName() => $this->faker->randomElement(Task::getStatusNames()),
            Task::getCreatedAtAttributeName() => $this->faker->dateTimeBetween('-1 month', '+11 month'),
        ];
    }
}
