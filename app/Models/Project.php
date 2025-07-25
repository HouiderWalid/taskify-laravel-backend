<?php

namespace App\Models;

use App\Models\helpers\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

class Project extends BaseModel
{
    use HasFactory;

    const TABLE_NAME = 'projects';

    const id_attribute_name = 'id';
    const creator_id_attribute_name = 'creator_id';
    const name_attribute_name = 'name';
    const description_attribute_name = 'description';
    const due_date_attribute_name = 'due_date';
    const created_at_attribute_name = 'created_at';
    const tasks_done_count_attribute_name = 'tasks_done_count';
    const tasks_count_attribute_name = 'tasks_count';
    const creator_attribute_name = 'creator';
    const tasks_attribute_name = 'tasks';
    const task_assigned_members_count_attribute_name = 'task_assigned_members_count';

    protected $fillable = [
        self::creator_id_attribute_name,
        self::name_attribute_name,
        self::description_attribute_name,
        self::due_date_attribute_name,
        self::created_at_attribute_name,
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, self::getCreatorIdAttributeName(), User::getIdAttributeName());
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class, Task::getProjectIdAttributeName(), self::getIdAttributeName());
    }

    public static function getIdAttributeName(): string
    {
        return self::id_attribute_name;
    }

    public static function getCreatorIdAttributeName(): string
    {
        return self::creator_id_attribute_name;
    }

    public static function getNameAttributeName(): string
    {
        return self::name_attribute_name;
    }

    public static function getDueDateAttributeName(): string
    {
        return self::due_date_attribute_name;
    }

    public static function getDescriptionAttributeName(): string
    {
        return self::description_attribute_name;
    }

    public static function getCreatedAtAttributeName(): string
    {
        return self::created_at_attribute_name;
    }

    public static function getTasksDoneCountAttributeName(): string
    {
        return self::tasks_done_count_attribute_name;
    }

    public static function getTasksCountAttributeName(): string
    {
        return self::tasks_count_attribute_name;
    }

    public static function getCreatorAttributeName(): string
    {
        return self::creator_attribute_name;
    }

    public static function getTasksAttributeName(): string
    {
        return self::tasks_attribute_name;
    }

    public static function getTaskAsssignedMembersCountAttributeName(): string
    {
        return self::task_assigned_members_count_attribute_name;
    }

    public function setName($name)
    {
        return $this->setAttribute(self::getNameAttributeName(), $name);
    }

    public function setDescription($description)
    {
        return $this->setAttribute(self::getDescriptionAttributeName(), $description);
    }

    public function getId()
    {
        return $this->getAttribute(self::getIdAttributeName());
    }

    public function getName()
    {
        return $this->getAttribute(self::getNameAttributeName());
    }

    public function getDescription()
    {
        return $this->getAttribute(self::getDescriptionAttributeName());
    }

    public function getDueDate()
    {
        return $this->getAttribute(self::getDueDateAttributeName());
    }

    public function getTasks()
    {
        $tasks = $this->{self::getTasksAttributeName()} ?? null;
        return $tasks instanceof Collection ? $tasks : new Collection();
    }
}
