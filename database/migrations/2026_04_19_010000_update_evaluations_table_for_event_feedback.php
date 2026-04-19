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
    Schema::table('evaluations', function (Blueprint $table) {
      if (! Schema::hasColumn('evaluations', 'event_id')) {
        $table->foreignId('event_id')
          ->nullable()
          ->after('evaluator_id')
          ->constrained('events', 'event_id')
          ->cascadeOnUpdate()
          ->cascadeOnDelete();
      }

      if (! Schema::hasColumn('evaluations', 'registration_id')) {
        $table->foreignId('registration_id')
          ->nullable()
          ->after('event_id')
          ->constrained('registrations', 'registration_id')
          ->cascadeOnUpdate()
          ->cascadeOnDelete();
      }

      if (! Schema::hasColumn('evaluations', 'participant_email')) {
        $table->string('participant_email')
          ->nullable()
          ->after('registration_id');
      }
    });

    Schema::table('evaluations', function (Blueprint $table) {
      $table->dropForeign(['paper_id']);
      $table->foreignId('paper_id')->nullable()->change();
      $table->foreign('paper_id')
        ->references('paper_id')
        ->on('papers')
        ->nullOnDelete()
        ->cascadeOnUpdate();

      $table->unique(['event_id', 'registration_id'], 'evaluations_event_registration_unique');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('evaluations', function (Blueprint $table) {
      $table->dropUnique('evaluations_event_registration_unique');

      if (Schema::hasColumn('evaluations', 'participant_email')) {
        $table->dropColumn('participant_email');
      }

      if (Schema::hasColumn('evaluations', 'registration_id')) {
        $table->dropConstrainedForeignId('registration_id');
      }

      if (Schema::hasColumn('evaluations', 'event_id')) {
        $table->dropConstrainedForeignId('event_id');
      }

      $table->dropForeign(['paper_id']);
      $table->foreignId('paper_id')->nullable(false)->change();
      $table->foreign('paper_id')
        ->references('paper_id')
        ->on('papers')
        ->cascadeOnUpdate()
        ->cascadeOnDelete();
    });
  }
};
