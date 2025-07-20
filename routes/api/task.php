<?php

use App\Classes\Helpers\UserPermission;
use App\Http\Controllers\TaskController;
use App\Http\Middleware\PermissionGuard;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', PermissionGuard::class . ':' . UserPermission::VIEW_TASKS])
    ->prefix('/task')
    ->group(function () {

        Route::prefix('/form')
            ->group(function () {
                Route::get('/projects', [TaskController::class, 'getFormProjects']);
                Route::get('/members', [TaskController::class, 'getFormMembers']);
            });

        Route::middleware(['auth:sanctum', PermissionGuard::class . ':' . UserPermission::CREATE_TASK])
            ->post('/', [TaskController::class, 'createTask']);
        Route::middleware(['auth:sanctum', PermissionGuard::class . ':' . UserPermission::UPDATE_TASK])
            ->put('/{taskId}', [TaskController::class, 'updateTask']);
        Route::middleware(['auth:sanctum', PermissionGuard::class . ':' . UserPermission::DELETE_TASK])
            ->delete('/{taskId}', [TaskController::class, 'deleteTask']);
        Route::get('/', [TaskController::class, 'getFilteredTaskss']);
    });