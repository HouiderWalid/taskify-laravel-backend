<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\helpers\BaseUser;
use Carbon\Carbon;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Laravel\Sanctum\HasApiTokens;

class User extends BaseUser
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    const TABLE_NAME = 'users';

    const id_attribute_name = 'id';
    const role_id_attribute_name = 'role_id';
    const full_name_attribute_name = 'full_name';
    const email_attribute_name = 'email';
    const password_attribute_name = 'password';
    const email_verified_at_attribute_name = 'email_verified_at';
    const email_verified_attribute_name = 'email_verified';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        self::role_id_attribute_name,
        self::full_name_attribute_name,
        self::email_attribute_name,
        self::password_attribute_name,
    ];

    protected $appends = [
        self::email_verified_attribute_name
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        self::password_attribute_name,
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            self::email_verified_at_attribute_name => 'datetime',
            self::password_attribute_name => 'hashed',
        ];
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, self::getRoleIdAttributeName(), Role::getIdAttributeName());
    }

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'user_permissions', 'user_id', 'permission_id');
    }

    public static function getFullNameAttributeName(): string
    {
        return self::full_name_attribute_name;
    }

    public static function getEmailAttributeName(): string
    {
        return self::email_attribute_name;
    }

    public static function getPasswordAttributeName(): string
    {
        return self::password_attribute_name;
    }

    public static function getEmailVerifiedAtAttributeName(): string
    {
        return self::email_verified_at_attribute_name;
    }

    public static function getIdAttributeName(): string
    {
        return self::id_attribute_name;
    }

    public static function getRoleIdAttributeName(): string
    {
        return self::role_id_attribute_name;
    }

    public function getRole(): ?Role
    {
        $role = $this->role ?? null;
        return $role instanceof Role ? $role : null;
    }

    public function getRoleName()
    {
        return $this->getRole()?->getName();
    }

    public function getId()
    {
        return $this->getAttribute(self::getIdAttributeName());
    }

    public function getEmailVerifiedAt()
    {
        return $this->getAttribute(self::getEmailVerifiedAtAttributeName());
    }

    public function getEmailVerifiedAttribute(): bool
    {
        return $this->getEmailVerifiedAt() instanceof Carbon;
    }

    public function getPassword()
    {
        return $this->getAttribute(self::getPasswordAttributeName());
    }

    public function isAdmin(): bool
    {
        return $this->getRoleName() === Role::ADMIN_ROLE;
    }

    public function isManager(): bool
    {
        return $this->getRoleName() === Role::MANAGER_ROLE;
    }

    public function getDefaultPermissions(): ?Collection
    {
        return $this->getRole()?->getDefaultPermissions();
    }

    public function getPermissions(): Collection
    {
        $permissions = $this->permissions ?? null;
        return $permissions instanceof Collection ? $permissions : new Collection();
    }

    public function hasPermission($permissionName): bool
    {
        $permissions = $this->getPermissions();
        return $permissions->contains(
            fn(Permission $permission) => is_array($permissionName)
                ? in_array($permission->getName(), $permissionName)
                : $permission->getName() === $permissionName
        );
    }
}
