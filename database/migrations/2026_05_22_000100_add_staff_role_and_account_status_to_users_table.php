<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('users', 'account_status')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('account_status', 20)->default('active')->after('role');
            });
        }

        DB::table('users')
            ->whereNull('account_status')
            ->orWhere('account_status', '')
            ->update(['account_status' => 'active']);

        if (Schema::getConnection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY role ENUM('participant', 'organizer', 'evaluator', 'admin', 'staff') NOT NULL");
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('users', 'account_status')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('account_status');
            });
        }

        if (Schema::getConnection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY role ENUM('participant', 'organizer', 'evaluator', 'admin') NOT NULL");
        }
    }
};
