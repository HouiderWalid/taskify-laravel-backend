<?php

namespace App\Models;

use App\Models\helpers\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaskComment extends BaseModel
{
    const TABLE_NAME = 'task_comments';

    const id_attribute_name = 'id';
    const task_id_attribute_name = 'task_id';
    const user_id_attribute_name = 'user_id';
    const content_attribute_name = 'content';

    protected $fillable = [
        self::task_id_attribute_name,
        self::user_id_attribute_name,
        self::content_attribute_name,
    ];

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class, self::getTaskIdAttributeName(), Task::getIdAttributeName());
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, self::getUserIdAttributeName(), User::getIdAttributeName());
    }

    public static function getIdAttributeName(): string
    {
        return self::id_attribute_name;
    }

    public static function getTaskIdAttributeName(): string
    {
        return self::task_id_attribute_name;
    }

    public static function getUserIdAttributeName(): string
    {
        return self::user_id_attribute_name;
    }

    public static function getContentAttributeName(): string
    {
        return self::content_attribute_name;
    }
}
