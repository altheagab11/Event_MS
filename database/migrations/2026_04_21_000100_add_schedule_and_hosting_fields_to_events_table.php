<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::table('events', function (Blueprint $table) {
      $table->string('hosted_by')->nullable()->after('event_name');
      $table->string('attendance_format')->nullable()->after('event_type');
      $table->date('start_date')->nullable()->after('event_date');
      $table->date('end_date')->nullable()->after('start_date');
    });

    DB::table('events')->update([
      'start_date' => DB::raw('event_date'),
      'end_date' => DB::raw('event_date'),
    ]);

    DB::table('events')
      ->whereNull('attendance_format')
      ->update(['attendance_format' => 'Face-to-Face']);
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('events', function (Blueprint $table) {
      $table->dropColumn([
        'hosted_by',
        'attendance_format',
        'start_date',
        'end_date',
      ]);
    });
  }
};
