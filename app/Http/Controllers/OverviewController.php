<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Arr;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Route;

class OverviewController extends Controller
{
    public function getChartTasksCountData(Request $request)
    {
        return $this->tryInRestrictedContext($request, function (Request $request) {
            $tasksCount = DB::table(Task::TABLE_NAME)
                ->selectRaw(Arr::join(['count(', Task::getModelJoinedTo(Task::getIdAttributeName()), ') as tasks_count'], ''))
                ->selectRaw(Arr::join(['date_format(', Task::getModelJoinedTo(Task::getCreatedAtAttributeName()), ', "%m") as month'], ''))
                ->selectRaw(Arr::join(['date_format(', Task::getModelJoinedTo(Task::getCreatedAtAttributeName()), ', "%Y") as year'], ''))
                ->where( Task::getModelJoinedTo(Task::getCreatedAtAttributeName()), '<', Carbon::now()->addMonths(6))
                ->where( Task::getModelJoinedTo(Task::getCreatedAtAttributeName()), '>', Carbon::now()->subMonths(6))
                ->groupBy('month', 'year')
                ->orderBy('year')
                ->orderBy('month')
                ->get();

            return $this->apiResponse(200, $tasksCount);
        });
    }

    public function getChartTasksDoneCountData(Request $request)
    {
        return $this->tryInRestrictedContext($request, function (Request $request) {

            $tasksCount = DB::table(Task::TABLE_NAME)
                ->selectRaw(Arr::join(['count(', Task::getModelJoinedTo(Task::getIdAttributeName()), ') as tasks_count'], ''))
                ->selectRaw(Arr::join(['date_format(', Task::getModelJoinedTo(Task::getCreatedAtAttributeName()), ', "%m") as month'], ''))
                ->whereRaw(Arr::join(['year(', Task::getModelJoinedTo(Task::getCreatedAtAttributeName()). ')=', Carbon::now()->year], ''))
                ->where(Task::getModelJoinedTo(Task::getStatusAttributeName()), Task::DONE_STATUS)
                ->groupBy('month')
                ->orderBy('month')
                ->get();

            return $this->apiResponse(200, $tasksCount);
        });
    }

    public function getProjectsCount(Request $request)
    {
        return $this->tryInRestrictedContext($request, function (Request $request) {
            return $this->apiResponse(200, Project::count());
        });
    }

    public function getLastSevenDaysTasksCount(Request $request)
    {
        return $this->tryInRestrictedContext($request, function (Request $request) {

            $lastSevenDays = Carbon::now()->subDays(7);
            $lastSevenDaysTasksCount = Task::where(Task::getCreatedAtAttributeName(), '>', $lastSevenDays)
                ->where(Task::getCreatedAtAttributeName(), '<=', Carbon::now())
                ->count();
            return $this->apiResponse(200, $lastSevenDaysTasksCount);
        });
    }

    public function getLastSevenDaysTasksDoneCount(Request $request)
    {
        return $this->tryInRestrictedContext($request, function (Request $request) {

            $lastSevenDays = Carbon::now()->subDays(7);
            $lastSevenDaystasksDoneCount = Task::where(Task::getCreatedAtAttributeName(), '>', $lastSevenDays)
                ->where(Task::getCreatedAtAttributeName(), '<=', Carbon::now())
                ->where(Task::getStatusAttributeName(), Task::DONE_STATUS)
                ->count();
            return $this->apiResponse(200, $lastSevenDaystasksDoneCount);
        });
    }
}
