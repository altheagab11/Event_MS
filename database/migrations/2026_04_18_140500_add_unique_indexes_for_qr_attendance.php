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
    Schema::table('digital_ids', function (Blueprint $table) {
      $table->unique('qr_code', 'digital_ids_qr_code_unique');
    });

    Schema::table('attendance', function (Blueprint $table) {
      $table->unique('registration_id', 'attendance_registration_id_unique');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('attendance', function (Blueprint $table) {
      $table->dropUnique('attendance_registration_id_unique');
    });

    Schema::table('digital_ids', function (Blueprint $table) {
      $table->dropUnique('digital_ids_qr_code_unique');
    });
  }
};
