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
    Schema::create('registration_verification_codes', function (Blueprint $table) {
      $table->id();
      $table->foreignId('event_id')
        ->constrained('events', 'event_id')
        ->cascadeOnUpdate()
        ->cascadeOnDelete();
      $table->string('email');
      $table->string('verification_code_hash');
      $table->json('payload');
      $table->string('paper_temp_path')->nullable();
      $table->enum('status', ['pending', 'verified', 'expired', 'failed'])->default('pending');
      $table->unsignedTinyInteger('attempts')->default(0);
      $table->timestamp('expires_at');
      $table->timestamp('verified_at')->nullable();
      $table->timestamps();

      $table->index(['email', 'event_id', 'status'], 'registration_verification_lookup_idx');
      $table->index('expires_at');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('registration_verification_codes');
  }
};
