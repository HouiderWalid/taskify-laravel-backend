<?php

namespace App\Models;

use App\Models\helpers\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Summary of Task
 */
class Task extends BaseModel
{
    use HasFactory;
    
    const TABLE_NAME = 'tasks';

    const TODO_STATUS = 'todo';
    const IN_PROGRESS_STATUS = 'in_progress';
    const DONE_STATUS = 'done';
    const DEFAULT_STATUS = self::TODO_STATUS;

    const LOW_PRIORITY = 'low';
    const MEDIUM_PRIORITY = 'medium';
    const HIGH_PRIORITY = 'high';
    const DEFAULT_PRIORITY = self::MEDIUM_PRIORITY;

    const id_attribute_name = 'id';
    const project_id_attribute_name = 'project_id';
    const assigned_to_user_id_attribute_name = 'assigned_to_user_id';
    const title_attribute_name = 'title';
    const description_attribute_name = 'description';
    const status_attribute_name = 'status';
    const priority_attribute_name = 'priority';
    const due_date_attribute_name = 'due_date';
    const created_at_attribute_name = 'created_at';
    const assigned_to_user_attribute_name = 'assignedToUser';
    const project_attribute_name = 'project';

    protected $fillable = [
        self::project_id_attribute_name,
        self::assigned_to_user_id_attribute_name,
        self::title_attribute_name,
        self::description_attribute_name,
        self::status_attribute_name,
        self::priority_attribute_name,
        self::due_date_attribute_name,
        self::created_at_attribute_name,
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

    public static function getCreatedAtAttributeName(): string
    {
        return self::created_at_attribute_name;
    }

    public static function getAssignedToUserAttributeName(): string
    {
        return self::assigned_to_user_attribute_name;
    }

    public static function getProjectAttributeName(): string
    {
        return self::project_attribute_name;
    }

    public static function getPriorityNames(): array
    {
        return [
            self::LOW_PRIORITY,
            self::MEDIUM_PRIORITY,
            self::HIGH_PRIORITY,
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

    public function getId(): string
    {
        return $this->getAttribute(self::getIdAttributeName());
    }

    public function getTitle(): string
    {
        return $this->getAttribute(self::getTitleAttributeName());
    }

    public function getDescription(): string
    {
        return $this->getAttribute(self::getDescriptionAttributeName());
    }

    public function getPriority(): string
    {
        return $this->getAttribute(self::getPriorityAttributeName());
    }

    public function getStatus(): string
    {
        return $this->getAttribute(self::getStatusAttributeName());
    }

    public function getDueDate(): string
    {
        return $this->getAttribute(self::getDueDateAttributeName());
    }

    public function isDone(): string
    {
        return $this->getStatus() === self::DONE_STATUS;
    }

    public function getCreatedAt()
    {
        return $this->getAttribute(self::getCreatedAtAttributeName());
    }

    public function getAssignedToUser(): ?User
    {
        $user = $this->{self::getAssignedToUserAttributeName()} ?? null;
        return $user instanceof User ? $user : null;
    }

    public function getProject(): ?Project
    {
        $project = $this->{self::getProjectAttributeName()} ?? null;
        return $project instanceof Project ? $project : null;
    }
}
