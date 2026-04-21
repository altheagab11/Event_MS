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
    Schema::table('registrations', function (Blueprint $table) {
      if (! Schema::hasColumn('registrations', 'evaluation_reminder_sent_at')) {
        $table->timestamp('evaluation_reminder_sent_at')
          ->nullable()
          ->after('status');
      }

      if (! Schema::hasColumn('registrations', 'evaluation_reminder_status')) {
        $table->string('evaluation_reminder_status')
          ->nullable()
          ->after('evaluation_reminder_sent_at');
      }

      $table->index(['event_id', 'evaluation_reminder_sent_at'], 'registrations_event_reminder_idx');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('registrations', function (Blueprint $table) {
      $table->dropIndex('registrations_event_reminder_idx');

      if (Schema::hasColumn('registrations', 'evaluation_reminder_status')) {
        $table->dropColumn('evaluation_reminder_status');
      }

      if (Schema::hasColumn('registrations', 'evaluation_reminder_sent_at')) {
        $table->dropColumn('evaluation_reminder_sent_at');
      }
    });
  }
};
