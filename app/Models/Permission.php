<?php

namespace App\Models;

use App\Models\helpers\BaseModel;
use Database\Factories\PermissionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use function Laravel\Prompts\select;

/**
 * @method static PermissionFactory factory(...$parameters)
 */
class Permission extends BaseModel
{
    use HasFactory;

    const TABLE_NAME = 'permissions';

    const id_attribute_name = 'id';
    const name_attribute_name = 'name';

    protected $fillable = [
        self::name_attribute_name
    ];

    public $timestamps = false;

    public static function getIdAttributeName(): string
    {
        return self::id_attribute_name;
    }

    public static function getNameAttributeName(): string
    {
        return self::name_attribute_name;
    }

    public function getName()
    {
        return $this->getAttribute(self::getNameAttributeName());
    }
}
