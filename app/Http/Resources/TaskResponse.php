<?php

namespace App\Http\Resources;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Str;

class TaskResponse extends ResourceCollection
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        $authenticatedUser = $request->user();
        if (!($authenticatedUser instanceof User)) {
            return [];
        }

        return [
            'current_page' => $this->currentPage(),
            'data' => $this->collection->map(function (Task $task) use ($authenticatedUser) {

                $project = $task->getProject();
                
                $data = [
                    Task::getIdAttributeName() => $task->getId(),
                    Task::getTitleAttributeName() => $task->getTitle(),
                    Task::getDescriptionAttributeName() => $task->getDescription(),
                    Task::getDueDateAttributeName() => $task->getDueDate(),
                    Task::getPriorityAttributeName() => $task->getPriority(),
                    Task::getStatusAttributeName() => $task->getStatus(),
                    Task::getCreatedAtAttributeName() => $task->getCreatedAt(),
                    Task::getProjectAttributeName() => $project ? [
                        Project::getIdAttributeName() => $project->getId(),
                        Project::getNameAttributeName() => $project->getName(),
                    ] : null,
                ];

                if ($authenticatedUser->isAdmin()) {
                    $assignedToUser = $task->getAssignedToUser();
                    $data[Str::snake(Task::getAssignedToUserAttributeName())] = $assignedToUser ? [
                        User::getIdAttributeName() => $assignedToUser->getId(),
                        User::getFullNameAttributeName() => $assignedToUser->getFullName(),
                    ] : null;
                }

                return $data;
            })->toArray(),
            'per_page' => $this->perPage(),
            'total' => $this->total()
        ];
    }
}
