<?php

use App\Classes\Helpers\UserPermission;
use App\Http\Controllers\ProjectController;
use App\Http\Middleware\PermissionGuard;
use Illuminate\Support\Facades\Route;

Route::prefix('project')
    ->group(function () {
        Route::middleware(['auth:sanctum', PermissionGuard::class . ':' . UserPermission::CREATE_PROJECT])
            ->post('/', [ProjectController::class, 'createProject']);
        Route::middleware(['auth:sanctum', PermissionGuard::class . ':' . UserPermission::UPDATE_PROJECT])
            ->put('/{projectId}', [ProjectController::class, 'updateProject']);
        Route::middleware(['auth:sanctum', PermissionGuard::class . ':' . UserPermission::DELETE_PROJECT])
            ->delete('/{projectId}', [ProjectController::class, 'deleteProject']);
        Route::middleware(['auth:sanctum', PermissionGuard::class . ':' . UserPermission::VIEW_PROJECTS])
            ->get('/', [ProjectController::class, 'getFilteredProjects']);
    });

