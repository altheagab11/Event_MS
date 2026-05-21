<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class ActivityLogger
{
    public static function log(
        string $action,
        ?string $module = null,
        ?string $description = null,
        ?User $user = null,
        ?Request $request = null,
    ): void {
        if (! Schema::hasTable('activity_logs')) {
            return;
        }

        $user = $user ?? auth()->user();
        $request = $request ?? request();

        ActivityLog::query()->create([
            'user_id' => $user?->id,
            'user_role' => $user?->role,
            'action' => $action,
            'module' => $module,
            'description' => $description,
            'ip_address' => $request?->ip(),
            'user_agent' => $request?->userAgent(),
            'created_at' => now(),
        ]);
    }
}
