<?php

namespace App\Models;

use App\Models\helpers\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends BaseModel
{
    const TABLE_NAME = 'tasks';

    const TODO_STATUS = 'todo';
    const IN_PROGRESS_STATUS = 'in_progress';
    const DONE_STATUS = 'done';
    const DEFAULT_STATUS = self::TODO_STATUS;

    const LOW_PRIORITY = 'low';
    const MEDIUM_PRIORITY = 'medium';
    const HEIGHT_PRIORITY = 'height';
    const DEFAULT_PRIORITY = self::MEDIUM_PRIORITY;

    const id_attribute_name = 'id';
    const project_id_attribute_name = 'project_id';
    const assigned_to_user_id_attribute_name = 'assigned_to_user_id';
    const title_attribute_name = 'title';
    const description_attribute_name = 'description';
    const status_attribute_name = 'status';
    const priority_attribute_name = 'priority';
    const due_date_attribute_name = 'due_date';

    protected $fillable = [
        self::project_id_attribute_name,
        self::assigned_to_user_id_attribute_name,
        self::title_attribute_name,
        self::description_attribute_name,
        self::status_attribute_name,
        self::priority_attribute_name,
        self::due_date_attribute_name,
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, self::getProjectIdAttributeName(), Project::getIdAttributeName());
    }

    public function assignedToUser(): BelongsTo
    {
        return $this->belongsTo(User::class, self::getAssignedToUserIdAttributeName(), User::getIdAttributeName());
    }

    public static function getIdAttributeName(): string
    {
        return self::id_attribute_name;
    }

    public static function getProjectIdAttributeName(): string
    {
        return self::project_id_attribute_name;
    }

    public static function getAssignedToUserIdAttributeName(): string
    {
        return self::assigned_to_user_id_attribute_name;
    }

    public static function getTitleAttributeName(): string
    {
        return self::title_attribute_name;
    }

    public static function getDescriptionAttributeName(): string
    {
        return self::description_attribute_name;
    }

    public static function getStatusAttributeName(): string
    {
        return self::status_attribute_name;
    }

    public static function getPriorityAttributeName(): string
    {
        return self::priority_attribute_name;
    }

    public static function getDueDateAttributeName(): string
    {
        return self::due_date_attribute_name;
    }

    public static function getPriorityNames(): array
    {
        return [
            self::LOW_PRIORITY,
            self::MEDIUM_PRIORITY,
            self::HEIGHT_PRIORITY,
        ];
    }

    public static function getStatusNames(): array
    {
        return [
            self::IN_PROGRESS_STATUS,
            self::DONE_STATUS,
            self::TODO_STATUS,
        ];
    }
}
