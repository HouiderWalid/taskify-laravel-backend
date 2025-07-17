<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class TaskController extends Controller
{

    public function createTasks(Request $request)
    {
        return $this->tryInRestrictedContext($request, function (Request $request) use ($request) {

            $validationRules = [
                'user_id' => ['required', User::existIn()],
                'project_id' => ['required', Project::existIn()],
                'title' => 'required|min:3|max:255',
                'description' => 'required|min:3|max:5000',
                'priority' => ['required', Rule::in(Task::getPriorityNames())],
                'status' => ['required', Rule::in(Task::getStatusNames())],
            ];

            $validator = Validator::make($request->all(), $validationRules);
            if ($validator->fails()) return $this->apiResponse(401, [], $validator->errors());

            $task = Task::create([
                Task::getProjectIdAttributeName() => $request->input('project_id'),
                Task::getAssignedToUserIdAttributeName() => $request->input('user_id'),
                Task::getTitleAttributeName() => $request->input('title'),
                Task::getDescriptionAttributeName() => $request->input('description'),
                Task::getPriorityAttributeName() => $request->input('priority'),
                Task::getStatusAttributeName() => $request->input('status'),
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

            $validationRules = [
                'user_id' => ['required', User::existIn()],
                'project_id' => ['required', Project::existIn()],
                'title' => 'required|min:3|max:255',
                'description' => 'required|min:3|max:5000',
                'priority' => ['required', Rule::in(Task::getPriorityNames())],
                'status' => ['required', Rule::in(Task::getStatusNames())],
            ];

            $validator = Validator::make($request->all(), $validationRules);
            if ($validator->fails()) return $this->apiResponse(401, [], $validator->errors());

            $isUpdated = $task->update([
                Task::getProjectIdAttributeName() => $request->input('project_id'),
                Task::getAssignedToUserIdAttributeName() => $request->input('user_id'),
                Task::getTitleAttributeName() => $request->input('title'),
                Task::getDescriptionAttributeName() => $request->input('description'),
                Task::getPriorityAttributeName() => $request->input('priority'),
                Task::getStatusAttributeName() => $request->input('status'),
            ]);

            if (!($isUpdated)) {
                throw new Exception("Task update failed");
            }

            return $this->getFilteredTasks($request, 'Task Updated Successfully.');
        });
    }

    public function getFilteredTasks(Request $request, string $message)
    {
        return $this->tryInRestrictedContext($request, function (Request $request, User $user) use ($message) {

            $tasks = Task::with('project');

            if (!$user->isAdmin()) {
                $tasks->where(Task::getAssignedToUserIdAttributeName(), $user->getId());
            }

            return $this->paginatedApiResponse($request, 200, $tasks, $message);
        });
    }

    public function deleteTask(Request $request, $taskId)
    {
        return $this->tryInRestrictedContext($request, function (Request $request) use ($taskId) {

            $task = Task::find($taskId);
            if (!($task instanceof Task)) {
                throw new Exception("Task not found");
            }

            if (!$task->update()){
                throw new Exception("Task delete failure");
            }

            return $this->getFilteredTasks($request, 'Task Deleted Successfully.');
        });
    }
}
