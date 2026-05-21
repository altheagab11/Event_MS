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

        Schema::table('registrations', function (Blueprint $table) {
            if (! Schema::hasColumn('registrations', 'attendance_status')) {
                if (Schema::hasColumn('registrations', 'attendance_mode')) {
                    $table->string('attendance_status', 50)->default('Absent')->after('attendance_mode');
                } else {
                    $table->string('attendance_status', 50)->default('Absent')->after('status');
                }
            }

            if (! Schema::hasColumn('registrations', 'checkin_method')) {
                $table->string('checkin_method', 50)->nullable()->after('attendance_status');
            }

            if (! Schema::hasColumn('registrations', 'checked_in_at')) {
                $table->timestamp('checked_in_at')->nullable()->after('checkin_method');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasTable('registrations')) {
            return;
        }

        Schema::table('registrations', function (Blueprint $table) {
            if (Schema::hasColumn('registrations', 'checked_in_at')) {
                $table->dropColumn('checked_in_at');
            }

            if (Schema::hasColumn('registrations', 'checkin_method')) {
                $table->dropColumn('checkin_method');
            }

            if (Schema::hasColumn('registrations', 'attendance_status')) {
                $table->dropColumn('attendance_status');
            }
        });
    }
};
