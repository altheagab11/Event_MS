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
    Schema::create('events', function (Blueprint $table) {
      $table->id('event_id');
      $table->string('event_name');
      $table->text('description')->nullable();
      $table->string('location')->nullable();
      $table->date('event_date');
    });

    Schema::create('registrations', function (Blueprint $table) {
      $table->id('registration_id');
      $table->foreignId('user_id')
        ->constrained('users')
        ->cascadeOnUpdate()
        ->cascadeOnDelete();
      $table->foreignId('event_id')
        ->constrained('events', 'event_id')
        ->cascadeOnUpdate()
        ->cascadeOnDelete();
      $table->timestamp('registration_date')->useCurrent();
      $table->enum('status', ['pending', 'approved', 'rejected', 'cancelled'])->default('pending');
    });

    Schema::create('attendance', function (Blueprint $table) {
      $table->id('attendance_id');
      $table->foreignId('registration_id')
        ->constrained('registrations', 'registration_id')
        ->cascadeOnUpdate()
        ->cascadeOnDelete();
      $table->dateTime('check_in_time')->nullable();
      $table->dateTime('check_out_time')->nullable();
    });

    Schema::create('digital_ids', function (Blueprint $table) {
      $table->id('digital_id');
      $table->foreignId('user_id')
        ->constrained('users')
        ->cascadeOnUpdate()
        ->cascadeOnDelete();
      $table->foreignId('event_id')
        ->constrained('events', 'event_id')
        ->cascadeOnUpdate()
        ->cascadeOnDelete();
      $table->string('qr_code');
      $table->timestamp('issued_at')->useCurrent();
    });

    Schema::create('papers', function (Blueprint $table) {
      $table->id('paper_id');
      $table->foreignId('user_id')
        ->constrained('users')
        ->cascadeOnUpdate()
        ->cascadeOnDelete();
      $table->foreignId('event_id')
        ->constrained('events', 'event_id')
        ->cascadeOnUpdate()
        ->cascadeOnDelete();
      $table->string('title');
      $table->string('file_path');
      $table->enum('status', ['submitted', 'under_review', 'accepted', 'rejected'])->default('submitted');
      $table->timestamp('created_at')->useCurrent();
    });

    Schema::create('evaluations', function (Blueprint $table) {
      $table->id('evaluation_id');
      $table->foreignId('paper_id')
        ->constrained('papers', 'paper_id')
        ->cascadeOnUpdate()
        ->cascadeOnDelete();
      $table->foreignId('evaluator_id')
        ->constrained('users')
        ->cascadeOnUpdate()
        ->cascadeOnDelete();
      $table->decimal('score', 5, 2);
      $table->text('comment')->nullable();
      $table->timestamp('evaluated_at')->useCurrent();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('evaluations');
    Schema::dropIfExists('papers');
    Schema::dropIfExists('digital_ids');
    Schema::dropIfExists('attendance');
    Schema::dropIfExists('registrations');
    Schema::dropIfExists('events');
  }
};
