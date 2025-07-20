<?php

namespace App\Http\Resources;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ProjectReponse extends ResourceCollection
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'current_page' => $this->currentPage(),
            'data' => $this->collection->map(function (Project $project) {
                $tasks = $project->getTasks();
                $doneTasks = $tasks->map(fn(Task $task) => $task->isDone());
                //$members = $tasks->unique(Task::getAssignedToUserIdAttributeName());
                return [
                    'id' => $project->getId(),
                    'name' => $project->getName(),
                    'description' => $project->getDescription(),
                    'due_date' => $project->getDueDate(),
                    'tasks_done_count' => $doneTasks->count(),
                    'tasks_count' => $tasks->count(),
                    //'teamMembers' => 
                ];
            })->toArray(),
            'per_page' => $this->perPage(),
            'total' => $this->total()
        ];
    }
}
