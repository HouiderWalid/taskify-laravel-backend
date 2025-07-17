<?php

use App\Classes\Helpers\UserPermission;
use App\Http\Controllers\ProjectController;
use App\Http\Middleware\PermissionGuard;
use Illuminate\Support\Facades\Route;

Route::prefix('project')
    ->group(function () {
        Route::middleware(['auth:sanctum', PermissionGuard::class . ':' . UserPermission::CREATE_PROJECT])
            ->post('/', [ProjectController::class, 'createProject']);
        Route::middleware(['auth:sanctum', PermissionGuard::class . ':' . UserPermission::CREATE_PROJECT])
            ->put('/', [ProjectController::class, 'updateProject']);
        Route::middleware(['auth:sanctum', PermissionGuard::class . ':' . UserPermission::VIEW_PROJECTS])
            ->get('/', [ProjectController::class, 'getFilteredProjects']);
    });

