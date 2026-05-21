<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    public const UPDATED_AT = null;

    protected $table = 'activity_logs';

    protected $primaryKey = 'log_id';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'user_role',
        'action',
        'module',
        'description',
        'ip_address',
        'user_agent',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function userDisplayName(): string
    {
        if ($this->relationLoaded('user') && $this->user !== null) {
            return $this->user->fullName();
        }

        return 'System';
    }

    public function roleLabel(): string
    {
        return match ((string) $this->user_role) {
            User::ROLE_SUPER_ADMIN => 'Super Admin',
            User::ROLE_ADMIN => 'Admin',
            User::ROLE_STAFF => 'Staff',
            default => $this->user_role !== null && $this->user_role !== ''
                ? ucfirst(str_replace('_', ' ', (string) $this->user_role))
                : '—',
        };
    }
}
