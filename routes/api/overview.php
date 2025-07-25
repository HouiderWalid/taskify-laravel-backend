<?php

use App\Classes\Helpers\UserPermission;
use App\Http\Controllers\OverviewController;
use App\Http\Middleware\PermissionGuard;

Route::middleware(['auth:sanctum', PermissionGuard::class . ':' . UserPermission::VIEW_OVERVIEW])
    ->prefix('overview')
    ->group(function () {
        Route::get('/chart_tasks_count', [OverviewController::class, 'getChartTasksCountData']);
        Route::get('/chart_tasks_done_count', [OverviewController::class, 'getChartTasksDoneCountData']);
        Route::get('/projects_count', [OverviewController::class, 'getProjectsCount']);
        Route::get('/last_7days_tasks_count', [OverviewController::class, 'getLastSevenDaysTasksCount']);
        Route::get('/last_7days_tasks_done_count', [OverviewController::class, 'getLastSevenDaysTasksDoneCount']);
    });