<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    public const ROLE_SUPER_ADMIN = 'super_admin';

    public const ROLE_ADMIN = 'admin';

    public const ROLE_STAFF = 'staff';

    public const STATUS_ACTIVE = 'active';

    public const STATUS_INACTIVE = 'inactive';

    protected $fillable = [
        'firstname',
        'lastname',
        'email',
        'password',
        'role',
        'account_status',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(Registration::class);
    }

    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class);
    }

    public function isSuperAdmin(): bool
    {
        return (string) $this->role === self::ROLE_SUPER_ADMIN;
    }

    public function isAdmin(): bool
    {
        return (string) $this->role === self::ROLE_ADMIN;
    }

    public function isStaff(): bool
    {
        return (string) $this->role === self::ROLE_STAFF;
    }

    public function canAccessPortal(): bool
    {
        return in_array((string) $this->role, self::portalRoles(), true);
    }

    public function canAccessDashboard(): bool
    {
        return $this->isSuperAdmin() || $this->isAdmin();
    }

    public function canManageAccounts(): bool
    {
        return $this->isSuperAdmin();
    }

    public function isActive(): bool
    {
        return (string) ($this->account_status ?? self::STATUS_ACTIVE) === self::STATUS_ACTIVE;
    }

    public function fullName(): string
    {
        return trim((string) $this->firstname.' '.(string) $this->lastname);
    }

    public function initials(): string
    {
        $name = $this->fullName();

        return $name !== '' ? strtoupper(mb_substr($name, 0, 1)) : 'U';
    }

    public function roleLabel(): string
    {
        return match ((string) $this->role) {
            self::ROLE_SUPER_ADMIN => 'Super Admin',
            self::ROLE_ADMIN => 'Admin',
            self::ROLE_STAFF => 'Staff',
            default => ucfirst(str_replace('_', ' ', (string) $this->role)),
        };
    }

    /**
     * @return array<int, string>
     */
    public static function portalRoles(): array
    {
        return [self::ROLE_SUPER_ADMIN, self::ROLE_ADMIN, self::ROLE_STAFF];
    }

    /**
     * Roles Super Admin may assign when creating or editing accounts.
     *
     * @return array<int, string>
     */
    public static function manageableAccountRoles(): array
    {
        return [self::ROLE_ADMIN, self::ROLE_STAFF];
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public static function splitFullName(string $fullName, array $attributes = []): array
    {
        $fullName = trim(preg_replace('/\s+/u', ' ', $fullName) ?? '');

        if ($fullName === '') {
            return array_merge($attributes, [
                'firstname' => 'Staff',
                'lastname' => 'User',
            ]);
        }

        $parts = explode(' ', $fullName, 2);

        return array_merge($attributes, [
            'firstname' => $parts[0],
            'lastname' => $parts[1] ?? '',
        ]);
    }
}
