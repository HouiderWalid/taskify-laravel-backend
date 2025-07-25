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
                $doneTasks = $tasks->filter(fn(Task $task) => $task->isDone());
                $assignedMembersCount = $tasks->pluck(Task::getAssignedToUserIdAttributeName())->unique()->count();
                
                return [
                    Project::getIdAttributeName() => $project->getId(),
                    Project::getNameAttributeName() => $project->getName(),
                    Project::getDescriptionAttributeName() => $project->getDescription(),
                    Project::getDueDateAttributeName() => $project->getDueDate(),
                    Project::getTasksDoneCountAttributeName() => $doneTasks->count(),
                    Project::getTasksCountAttributeName() => $tasks->count(),
                    Project::getTaskAsssignedMembersCountAttributeName() => $assignedMembersCount,
                ];
            })->toArray(),
            'per_page' => $this->perPage(),
            'total' => $this->total()
        ];
    }
}
