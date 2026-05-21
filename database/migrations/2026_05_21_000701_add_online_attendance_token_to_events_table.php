<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasTable('events')) {
            return;
        }

        if (Schema::hasColumn('events', 'online_attendance_token')) {
            return;
        }

        Schema::table('events', function (Blueprint $table) {
            if (Schema::hasColumn('events', 'attendance_format')) {
                $table->string('online_attendance_token', 64)->nullable()->unique()->after('attendance_format');
            } else {
                $table->string('online_attendance_token', 64)->nullable()->unique();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasTable('events') || ! Schema::hasColumn('events', 'online_attendance_token')) {
            return;
        }

        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('online_attendance_token');
        });
    }
};
