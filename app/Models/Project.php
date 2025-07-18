<?php

namespace App\Models;

use App\Models\helpers\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends BaseModel
{
    const TABLE_NAME = 'projects';

    const id_attribute_name = 'id';
    const owner_id_attribute_name = 'owner_id';
    const name_attribute_name = 'name';
    const description_attribute_name = 'description';
    const due_date_attribute_name = 'due_date';

    protected $fillable = [
        self::owner_id_attribute_name,
        self::name_attribute_name,
        self::description_attribute_name,
        self::due_date_attribute_name
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, self::getOwnerIdAttributeName(), User::getIdAttributeName());
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class, Task::getProjectIdAttributeName(), self::getIdAttributeName());
    }

    public static function getIdAttributeName(): string
    {
        return self::id_attribute_name;
    }

    public static function getOwnerIdAttributeName(): string
    {
        return self::owner_id_attribute_name;
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

    public function setName($name)
    {
        return $this->setAttribute(self::getNameAttributeName(), $name);
    }

    public function setDescription($description)
    {
        return $this->setAttribute(self::getDescriptionAttributeName(), $description);
    }
}
