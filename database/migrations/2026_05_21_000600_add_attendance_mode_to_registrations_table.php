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
        if (! Schema::hasTable('registrations')) {
            return;
        }

        if (Schema::hasColumn('registrations', 'attendance_mode')) {
            return;
        }

        Schema::table('registrations', function (Blueprint $table) {
            if (Schema::hasColumn('registrations', 'role')) {
                $table->string('attendance_mode', 50)->nullable()->after('role');
            } else {
                $table->string('attendance_mode', 50)->nullable()->after('status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasTable('registrations') || ! Schema::hasColumn('registrations', 'attendance_mode')) {
            return;
        }

        Schema::table('registrations', function (Blueprint $table) {
            $table->dropColumn('attendance_mode');
        });
    }
};
