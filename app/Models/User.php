<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\helpers\BaseModel;
use App\Models\helpers\BaseUser;
use App\Observers\UserObserver;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Laravel\Sanctum\HasApiTokens;

#[ObservedBy([UserObserver::class])]
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
    const created_at_attribute_name = 'created_at';
    const role_attribute_name = 'role';
    const permissions_attribute_name = 'permissions';

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

    public static function getCreatedAtAttributeName(): string
    {
        return self::created_at_attribute_name;
    }

    public static function getRoleAttributeName(): string
    {
        return self::role_attribute_name;
    }

    public static function getPermissionsAttributeName(): string
    {
        return self::permissions_attribute_name;
    }

    public function getRole(): ?Role
    {
        $role = $this->{self::getRoleAttributeName()} ?? null;
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

    public function getFullName()
    {
        return $this->getAttribute(self::getFullNameAttributeName());
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

    public function getAuthenticationData()
    {
        return User::where(User::getIdAttributeName(), $this->getId())
            ->with([
                BaseModel::getRelationInlineAttributes(self::getRoleAttributeName(), [
                    Role::getIdAttributeName(),
                    Role::getNameAttributeName(),
                ]),
                BaseModel::getRelationInlineAttributes(self::getPermissionsAttributeName(), [
                    Permission::getIdAttributeName(),
                    Permission::getNameAttributeName(),
                ])
            ])
            ->first();
    }

    public function initUserDefaultPermissions()
    {
        $role = $this->getRole();
        if ($role) {
            $this->permissions()->sync($role->getDefaultPermissions()->pluck(Permission::getIdAttributeName()));
        }

        return $this;
    }

    public function getTokenText()
    {
        return $this->createToken(config('app.name'))->plainTextToken;
    }
}
