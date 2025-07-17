<?php

namespace App\Classes\Helpers;

use App\Models\Role;

class UserPermission
{
    const VIEW_OVERVIEW = 'view-overview';
    const VIEW_PROJECTS = 'view-projects';
    const CREATE_PROJECT = 'create-project';
    const UPDATE_PROJECT = 'update-project';
    const DELETE_PROJECT = 'delete-project';
    const VIEW_TASKS = 'view-tasks';
    const CREATE_TASK = 'create-task';
    const UPDATE_TASK = 'update-task';
    const DELETE_TASK = 'delete-task';
    const UPDATE_TASK_STATUS = 'update-task-status';
    const VIEW_USERS = 'view-users';
    const CREATE_USER = 'create-user';
    const UPDATE_USER = 'update-user';
    const VIEW_CHAT = 'view-chat';
    const VIEW_SETTINGS = 'view-settings';
    const UPDATE_SETTINGS = 'update-settings';

    const ALL_PERMISSIONS = [
        self::VIEW_OVERVIEW,
        self::VIEW_PROJECTS,
        self::CREATE_PROJECT,
        self::UPDATE_PROJECT,
        self::DELETE_PROJECT,
        self::VIEW_TASKS,
        self::CREATE_TASK,
        self::UPDATE_TASK,
        self::DELETE_TASK,
        self::UPDATE_TASK_STATUS,
        self::VIEW_USERS,
        self::CREATE_USER,
        self::UPDATE_USER,
        self::VIEW_CHAT,
        self::VIEW_SETTINGS,
        self::UPDATE_SETTINGS,
    ];

    const ROLE_DEFAULT_PERMISSIONS = [
        Role::ADMIN_ROLE => [
            self::VIEW_OVERVIEW,
            self::VIEW_PROJECTS,
            self::CREATE_PROJECT,
            self::UPDATE_PROJECT,
            self::DELETE_PROJECT,
            self::VIEW_TASKS,
            self::CREATE_TASK,
            self::UPDATE_TASK,
            self::DELETE_TASK,
            self::UPDATE_TASK_STATUS,
            self::VIEW_USERS,
            self::CREATE_USER,
            self::UPDATE_USER,
            self::VIEW_CHAT,
            self::VIEW_SETTINGS,
            self::UPDATE_SETTINGS,
        ],
        Role::MANAGER_ROLE => [
            self::VIEW_OVERVIEW,
            self::VIEW_PROJECTS,
            self::CREATE_PROJECT,
            self::UPDATE_PROJECT,
            self::DELETE_PROJECT,
            self::VIEW_TASKS,
            self::CREATE_TASK,
            self::UPDATE_TASK,
            self::DELETE_TASK,
            self::UPDATE_TASK_STATUS,
            self::VIEW_CHAT,
            self::VIEW_SETTINGS,
            self::UPDATE_SETTINGS,
        ],
        Role::MEMBER_ROLE => [
            self::VIEW_OVERVIEW,
            self::VIEW_PROJECTS,
            self::VIEW_TASKS,
            self::UPDATE_TASK_STATUS,
            self::VIEW_CHAT,
            self::VIEW_SETTINGS,
            self::UPDATE_SETTINGS,
        ],
    ];
}
