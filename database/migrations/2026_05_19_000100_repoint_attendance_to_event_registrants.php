<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * attendance.registration_id stores event_registrants.event_registrant_id.
     */
    public function up(): void
    {
        if (! Schema::hasTable('event_registrants') || ! Schema::hasTable('attendance')) {
            return;
        }

        if (Schema::hasTable('registrations') && DB::table('attendance')->exists()) {
            DB::statement('
                UPDATE attendance a
                INNER JOIN registrations r ON a.registration_id = r.registration_id
                SET a.registration_id = r.event_registrant_id
                WHERE r.event_registrant_id IS NOT NULL
            ');
        }

        Schema::table('attendance', function (Blueprint $table) {
            $table->dropForeign('attendance_registration_id_foreign');
        });

        Schema::table('attendance', function (Blueprint $table) {
            $table->foreign('registration_id', 'attendance_registration_id_foreign')
                ->references('event_registrant_id')
                ->on('event_registrants')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('event_registrants') || ! Schema::hasTable('attendance')) {
            return;
        }

        Schema::table('attendance', function (Blueprint $table) {
            $table->dropForeign('attendance_registration_id_foreign');
        });

        Schema::table('attendance', function (Blueprint $table) {
            $table->foreign('registration_id', 'attendance_registration_id_foreign')
                ->references('registration_id')
                ->on('registrations')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
        });
    }
};
