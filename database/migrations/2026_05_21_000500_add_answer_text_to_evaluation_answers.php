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
        if (! Schema::hasTable('evaluation_answers')) {
            return;
        }

        if (! Schema::hasColumn('evaluation_answers', 'answer_text')) {
            Schema::table('evaluation_answers', function (Blueprint $table) {
                $table->text('answer_text')->nullable()->after('rating_value');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasTable('evaluation_answers')) {
            return;
        }

        if (Schema::hasColumn('evaluation_answers', 'answer_text')) {
            Schema::table('evaluation_answers', function (Blueprint $table) {
                $table->dropColumn('answer_text');
            });
        }
    }
};
