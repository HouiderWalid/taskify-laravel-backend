<?php

namespace App\Http\Controllers;

use App\Http\Resources\TaskResponse;
use App\Models\helpers\BaseModel;
use App\Models\Project;
use App\Models\Role;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class TaskController extends Controller
{

    public function createTask(Request $request)
    {
        return $this->tryInRestrictedContext($request, function (Request $request) {

            $timeZone = $request->query("timezone");
            $validationRules = [
                'member_id' => ['required', User::existIn()],
                'project_id' => ['required', Project::existIn()],
                'title' => 'required|min:3|max:255',
                'description' => 'required|min:3|max:5000',
                'priority' => ['required', Rule::in(Task::getPriorityNames())],
                'due_date' => [
                    'required',
                    'date',
                    function ($attribute, $value, $fail) use ($request, $timeZone) {
                        $date = Carbon::parse($value, $timeZone);
                        if (!$date->isFuture()) {
                            $fail('Please choose a date in the future.');
                        }
                    }
                ]
            ];

            $validator = Validator::make($request->all(), $validationRules);
            if ($validator->fails()) {
                return $this->apiResponse(401, [], $validator->errors());
            }

            $task = Task::create([
                Task::getProjectIdAttributeName() => $request->input('project_id'),
                Task::getAssignedToUserIdAttributeName() => $request->input('member_id'),
                Task::getTitleAttributeName() => $request->input('title'),
                Task::getDescriptionAttributeName() => $request->input('description'),
                Task::getDueDateAttributeName() => $request->input('due_date'),
                Task::getPriorityAttributeName() => $request->input('priority'),
            ]);

            if (!($task instanceof Task)) {
                throw new Exception("Task creation failed");
            }

            return $this->getFilteredTasks($request, 'Task Created Successfully.');
        });
    }

    public function updateTask(Request $request, $taskId)
    {
        return $this->tryInRestrictedContext($request, function (Request $request, User $user) use ($taskId) {

            $task = Task::find($taskId);
            if (!($task instanceof Task)) {
                throw new Exception("Task not found");
            }

            $timeZone = $request->query("timezone");
            $validationRules = [
                'member_id' => ['required', User::existIn()],
                'project_id' => ['required', Project::existIn()],
                'title' => 'required|min:3|max:255',
                'description' => 'required|min:3|max:5000',
                'priority' => ['required', Rule::in(Task::getPriorityNames())],
                'due_date' => [
                    'required',
                    'date',
                    function ($attribute, $value, $fail) use ($request, $timeZone) {
                        $date = Carbon::parse($value, $timeZone);
                        if (!$date->isFuture()) {
                            $fail('Please choose a date in the future.');
                        }
                    }
                ]
            ];

            $validator = Validator::make($request->all(), $validationRules);
            if ($validator->fails()) {
                return $this->apiResponse(401, [], $validator->errors());
            }

            $isUpdated = $task->update([
                Task::getProjectIdAttributeName() => $request->input('project_id'),
                Task::getAssignedToUserIdAttributeName() => $request->input('member_id'),
                Task::getTitleAttributeName() => $request->input('title'),
                Task::getDescriptionAttributeName() => $request->input('description'),
                Task::getDueDateAttributeName() => $request->input('due_date'),
                Task::getPriorityAttributeName() => $request->input('priority'),
            ]);

            if (!$isUpdated) {
                throw new Exception("Task update failed");
            }

            return $this->getFilteredTasks($request, 'Task Updated Successfully.');
        });
    }

    public function getFilteredTasks(Request $request, string $message = '')
    {
        return $this->tryInRestrictedContext($request, function (Request $request, User $user) use ($message) {

            $tasks = Task::orderByDesc(Task::getIdAttributeName())
                ->orderByDesc(Task::getCreatedAtAttributeName());

            if (!$user->isAdmin()) {
                $tasks->where(Task::getAssignedToUserIdAttributeName(), $user->getId());
            }

            return $this->paginatedApiResponse($request, 200, $tasks, $message, TaskResponse::class);
        });
    }

    public function deleteTask(Request $request, $taskId)
    {
        return $this->tryInRestrictedContext($request, function (Request $request) use ($taskId) {

            $task = Task::find($taskId);
            if (!($task instanceof Task)) {
                throw new Exception("Task not found");
            }

            if (!$task->delete()) {
                throw new Exception("Task delete failure");
            }

            return $this->getFilteredTasks($request, 'Task Deleted Successfully.');
        });
    }

    public function getFormProjects(Request $request)
    {
        return $this->tryInRestrictedContext($request, function (Request $request, User $user) {

            $projects = Project::select([
                Project::getIdAttributeName(),
                Project::getNameAttributeName()
            ]);

            if (!$user->isAdmin()) {
                $projects->where(Project::getCreatorIdAttributeName(), $user->getId());
            }

            $projects = $projects->orderByDesc(Project::getCreatedAtAttributeName())
                ->get();

            return $this->apiResponse(200, $projects);
        });
    }

    public function getFormMembers(Request $request)
    {
        return $this->tryInRestrictedContext($request, function () {

            $users = User::whereHas('role', function ($query) {
                $query->where(Role::getNameAttributeName(), Role::MEMBER_ROLE);
            })->select([
                        User::getIdAttributeName(),
                        User::getFullNameAttributeName()
                    ])->orderByDesc(User::getCreatedAtAttributeName())
                ->get();

            return $this->apiResponse(200, $users);
        });
    }
}
