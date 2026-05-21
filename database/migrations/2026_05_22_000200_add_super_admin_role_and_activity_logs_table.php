<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::getConnection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY role ENUM('participant', 'organizer', 'evaluator', 'super_admin', 'admin', 'staff') NOT NULL");
        }

        if (Schema::hasTable('users') && ! DB::table('users')->where('role', 'super_admin')->exists()) {
            DB::table('users')
                ->where('email', 'admin@nu.edu.ph')
                ->update(['role' => 'super_admin']);
        }

        if (! Schema::hasTable('activity_logs')) {
            Schema::create('activity_logs', function (Blueprint $table) {
                $table->id('log_id');
                $table->unsignedBigInteger('user_id')->nullable();
                $table->string('user_role', 50)->nullable();
                $table->string('action', 100);
                $table->string('module', 100)->nullable();
                $table->text('description')->nullable();
                $table->string('ip_address', 100)->nullable();
                $table->text('user_agent')->nullable();
                $table->timestamp('created_at')->nullable()->useCurrent();

                $table->index('user_id');
                $table->index('user_role');
                $table->index('module');
                $table->index('action');
                $table->index('created_at');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');

        if (Schema::getConnection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY role ENUM('participant', 'organizer', 'evaluator', 'admin', 'staff') NOT NULL");
        }
    }
};
