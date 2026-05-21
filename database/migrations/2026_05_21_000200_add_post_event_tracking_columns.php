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
        Schema::table('events', function (Blueprint $table) {
            $table->timestamp('evaluation_links_sent_at')->nullable()->after('status');
            $table->timestamp('attendance_certificates_distributed_at')->nullable()->after('evaluation_links_sent_at');
        });

        Schema::table('registrations', function (Blueprint $table) {
            $table->timestamp('attendance_certificate_sent_at')->nullable()->after('evaluation_reminder_status');
            $table->timestamp('participation_certificate_sent_at')->nullable()->after('attendance_certificate_sent_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            $table->dropColumn([
                'attendance_certificate_sent_at',
                'participation_certificate_sent_at',
            ]);
        });

        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn([
                'evaluation_links_sent_at',
                'attendance_certificates_distributed_at',
            ]);
        });
    }
};
