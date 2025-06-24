<?php

namespace App\Models;

use App\Models\helpers\BaseModel;
use Database\Factories\RoleFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @method static RoleFactory factory(...$parameters)
 */
class Role extends BaseModel
{
    use HasFactory;

    const TABLE_NAME = 'roles';

    const ADMIN_ROLE = 'admin';
    const MANAGER_ROLE = 'manager';
    const MEMBER_ROLE = 'member';

    const id_attribute_name = 'id';
    const name_attribute_name = 'name';
    const permissions_attribute_name = 'permissions';

    protected $fillable = [
        self::name_attribute_name,
        self::permissions_attribute_name
    ];

    public static function getIdAttributeName(): string
    {
        return self::id_attribute_name;
    }

    public static function getNameAttributeName(): string
    {
        return self::name_attribute_name;
    }

    public static function getPermissionsAttributeName(): string
    {
        return self::permissions_attribute_name;
    }

    public static function getRoles(): array
    {
        return [
            self::ADMIN_ROLE,
            self::MANAGER_ROLE,
            self::MEMBER_ROLE
        ];
    }

    public function getId()
    {
        return $this->getAttribute(self::getIdAttributeName());
    }
}
