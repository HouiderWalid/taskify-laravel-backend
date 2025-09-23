<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Role;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Helpers\CustomRefreshDatabase;
use Tests\TestCase;

class TaskTest extends TestCase
{

    use RefreshDatabase;

    protected $seed = true;
    
    public function getAUserToken()
    {
        $email = fake()->email();
        $password = fake()->password();
        $user = User::factory()->credentials($email, $password)->giveRole(Role::ADMIN_ROLE)->create();
        $token = $user instanceof User ? $user->getTokenText() : null;

        $this->assertNotNull($token);

        return $token;
    }

    public function test_successfull_create_task(): void
    {
        $userToken = $this->getAUserToken();

        $name = fake()->name();
        $description = fake()->text();
        $dueDate = fake()->dateTimeBetween('now', '+1 month')->format('Y-m-d H:i:s');
        $project = Project::factory()->create();
        $user = User::factory()->create();
        $priority = fake()->randomElement(['low', 'medium', 'high']);
        $timeZone = fake()->timezone();

        $response = $this->post('/api/task?timezone=' . $timeZone, [
            'title' => $name,
            'description' => $description,
            'due_date' => $dueDate,
            'project_id' => $project->id,
            'member_id' => $user->id,
            'priority' => $priority
        ], [
            'Authorization' => 'Bearer ' . $userToken,
        ]);

        $response->assertStatus(200);
        $response->assertJsonPath('code', 200);

        $taskCreated = Task::where(Task::getTitleAttributeName(), $name)
            ->where(Task::getDescriptionAttributeName(), $description)
            ->where(Task::getDueDateAttributeName(), $dueDate)
            ->where(Task::getProjectIdAttributeName(), $project->id)
            ->where(Task::getAssignedToUserIdAttributeName(), $user->id)
            ->where(Task::getPriorityAttributeName(), $priority)
            ->first();

        $this->assertNotNull($taskCreated);
    }

    public function test_successfull_update_task()
    {
        $userToken = $this->getAUserToken();

        $name = fake()->name();
        $description = fake()->text();
        $dueDate = fake()->dateTimeBetween('now', '+1 month')->format('Y-m-d H:i:s');
        $project = Project::factory()->create();
        $user = User::factory()->create();
        $priority = fake()->randomElement(['low', 'medium', 'high']);
        $timeZone = fake()->timezone();

        $task = Task::factory()->create();

        $response = $this->put('/api/task/' . $task->id . '?timezone=' . $timeZone, [
            'title' => $name,
            'description' => $description,
            'due_date' => $dueDate,
            'project_id' => $project->id,
            'member_id' => $user->id,
            'priority' => $priority
        ], [
            'Authorization' => 'Bearer ' . $userToken,
        ]);

        $response->assertStatus(200);
        $response->assertJsonPath('code', 200);

        $taskUpdated = Task::where(Task::getTitleAttributeName(), $name)
            ->where(Task::getDescriptionAttributeName(), $description)
            ->where(Task::getDueDateAttributeName(), $dueDate)
            ->where(Task::getProjectIdAttributeName(), $project->id)
            ->where(Task::getAssignedToUserIdAttributeName(), $user->id)
            ->where(Task::getPriorityAttributeName(), $priority)
            ->first();

        $this->assertNotNull($taskUpdated);
    }

    public function test_successfull_delete_task()
    {
        $userToken = $this->getAUserToken();

        $task = Task::factory()->create();

        $response = $this->delete('/api/task/' . $task->id, [], [
            'Authorization' => 'Bearer ' . $userToken,
        ]);

        $response->assertStatus(200);
        $response->assertJsonPath('code', 200);

        $taskDeleted = Task::where(Task::getIdAttributeName(), $task->id)->first();

        $this->assertNull($taskDeleted);
    }
}
