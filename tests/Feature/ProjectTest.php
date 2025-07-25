<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Role;
use App\Models\User;
use Tests\Helpers\CustomRefreshDatabase;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    use CustomRefreshDatabase;

    public function getAUserToken()
    {
        $email = fake()->email();
        $password = fake()->password();
        $user = User::factory()->credentials($email, $password)->giveRole(Role::ADMIN_ROLE)->create();
        $token = $user instanceof User ? $user->getTokenText() : null;

        $this->assertNotNull($token);

        return $token;
    }

    /**
     * A basic feature test example.
     */
    public function test_successfull_create_project(): void
    {
        $userToken = $this->getAUserToken();

        $name = fake()->name();
        $description = fake()->text();
        $dueDate = fake()->dateTimeBetween('now', '+1 month')->format('Y-m-d H:i:s');
        $timeZone = fake()->timezone();

        $response = $this->post('/api/project?timezone=' . $timeZone, [
            'name' => $name,
            'description' => $description,
            'due_date' => $dueDate
        ], [
            'Authorization' => 'Bearer ' . $userToken,
        ]);

        $response->assertStatus(200);
        $response->assertJsonPath('code', 200);

        $projectCreated = Project::where(Project::getNameAttributeName(), $name)
            ->where(Project::getDescriptionAttributeName(), $description)
            ->where(Project::getDueDateAttributeName(), $dueDate)
            ->first();

        $this->assertNotNull($projectCreated);
    }

    public function test_successfull_update_project()
    {
        $userToken = $this->getAUserToken();

        $projectToUpdate = Project::factory()->create();
        $projectId = $projectToUpdate->getId();

        $name = fake()->name();
        $description = fake()->text();
        $dueDate = fake()->dateTimeBetween('now', '+1 month')->format('Y-m-d H:i:s');
        $timeZone = fake()->timezone();

        $response = $this->put("/api/project/$projectId?timezone=" . $timeZone, [
            'name' => $name,
            'description' => $description,
            'due_date' => $dueDate
        ], [
            'Authorization' => 'Bearer ' . $userToken,
        ]);

        $response->assertStatus(200);
        $response->assertJsonPath('code', 200);

        $projectUpdated = Project::where(Project::getNameAttributeName(), $name)
            ->where(Project::getDescriptionAttributeName(), $description)
            ->where(Project::getDueDateAttributeName(), $dueDate)
            ->first();

        $this->assertNotNull($projectUpdated);
    }

    public function test_successfull_delete_project(){
        $userToken = $this->getAUserToken();
        
        $projectToDelete = Project::factory()->create();
        $projectId = $projectToDelete->getId();

        $response = $this->delete("/api/project/$projectId", [], [
            'Authorization' => 'Bearer ' . $userToken,
        ]);

        $response->assertStatus(200);
        $response->assertJsonPath('code', 200);

        $projectDeleted = Project::find($projectId);
        $this->assertNull($projectDeleted);
    }
}
