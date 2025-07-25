<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProjectReponse;
use App\Models\Project;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ProjectController extends Controller
{
    public function createProject(Request $request)
    {
        return $this->tryInRestrictedContext($request, function (Request $request, User $user) {

            $timeZone = $request->query("timezone");
            $validationRules = [
                'name' => 'required|min:3|max:100',
                'description' => 'required|min:3|max:5000',
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

            $project = Project::create([
                Project::getCreatorIdAttributeName() => $user->getId(),
                Project::getNameAttributeName() => $request->get('name'),
                Project::getDescriptionAttributeName() => $request->get('description'),
                Project::getDueDateAttributeName() => $request->get('due_date'),
            ]);

            if (!($project instanceof Project)) {
                throw new Exception("Project not created");
            }

            return $this->getFilteredProjects($request, 'Project Created Successfully.');
        });
    }

    public function updateProject(Request $request, $projectId)
    {
        return $this->tryInRestrictedContext($request, function (Request $request, User $user) use ($projectId) {

            $project = Project::where(Project::getIdAttributeName(), $projectId);

            if (!$user->isAdmin()) {
                $project->where(Project::getCreatorIdAttributeName(), $user->getId());
            }

            $project = $project->first();

            if (!($project instanceof Project)) {
                throw new Exception('Project not found.');
            }

            $timeZone = $request->query("timezone");
            $validationRules = [
                'name' => 'required|min:3|max:100',
                'description' => 'required|min:3|max:5000',
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

            $isUpdated = $project->update([
                Project::getNameAttributeName() => $request->input('name'),
                Project::getDescriptionAttributeName() => $request->input('description'),
                Project::getDueDateAttributeName() => $request->input('due_date'),
            ]);

            if (!$isUpdated) {
                throw new Exception("Project update failure.");
            }

            return $this->getFilteredProjects($request, 'Project Updated Successfully.');
        });
    }

    public function getFilteredProjects(Request $request, $message = '')
    {
        return $this->tryInRestrictedContext($request, function (Request $request, User $user) use ($message) {

            $projects = Project::orderByDesc(Project::getCreatedAtAttributeName());

            if (!$user->isAdmin()) {
                $projects->where(Project::getCreatorIdAttributeName(), $user->getId());
            }

            return $this->paginatedApiResponse(
                $request,
                200,
                $projects,
                $message,
                ProjectReponse::class
            );
        });
    }

    public function deleteProject(Request $request, $projectId)
    {
        return $this->tryInRestrictedContext($request, function (Request $request, User $user) use ($projectId) {

            $project = Project::where(Project::getIdAttributeName(), $projectId);

            if (!$user->isAdmin()) {
                $project->where(Project::getCreatorIdAttributeName(), $user->getId());
            }

            $project = $project->first();

            if (!($project instanceof Project)) {
                throw new Exception("Project not found.");
            }

            if (!$project->delete()) {
                throw new Exception("Project delete failure.");
            }

            return $this->getFilteredProjects($request, 'Project Deleted Successfully.');
        });
    }
}
