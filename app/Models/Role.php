<?php

namespace App\Models;

use App\Models\helpers\BaseModel;
use Database\Factories\RoleFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;

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

    protected $fillable = [
        self::name_attribute_name
    ];

    public function defaultPermissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'role_permissions', 'role_id', 'permission_id');
    }

    public static function getIdAttributeName(): string
    {
        return self::id_attribute_name;
    }

    public static function getNameAttributeName(): string
    {
        return self::name_attribute_name;
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

    public function getName()
    {
        return $this->getAttribute(self::getNameAttributeName());
    }

    public function getDefaultPermissions(): Collection
    {
        $defaultPermissions = $this->defaultPermissions ?? null;
        return $defaultPermissions instanceof Collection ? $defaultPermissions : new Collection();
    }
}
