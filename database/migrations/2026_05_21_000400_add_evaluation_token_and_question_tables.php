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
        Schema::table('registrations', function (Blueprint $table) {
            if (! Schema::hasColumn('registrations', 'evaluation_token')) {
                $table->string('evaluation_token', 64)->nullable()->unique()->after('participation_certificate_sent_at');
            }

            if (! Schema::hasColumn('registrations', 'evaluation_submitted_at')) {
                $table->timestamp('evaluation_submitted_at')->nullable()->after('evaluation_token');
            }
        });

        if (Schema::hasTable('evaluations') && Schema::hasColumn('evaluations', 'evaluator_id')) {
            Schema::table('evaluations', function (Blueprint $table) {
                $table->dropForeign(['evaluator_id']);
            });

            Schema::table('evaluations', function (Blueprint $table) {
                $table->foreignId('evaluator_id')->nullable()->change();
                $table->foreign('evaluator_id')
                    ->references('id')
                    ->on('users')
                    ->nullOnDelete()
                    ->cascadeOnUpdate();
            });
        }

        if (! Schema::hasTable('evaluation_questions')) {
            Schema::create('evaluation_questions', function (Blueprint $table) {
                $table->id('question_id');
                $table->foreignId('event_id')
                    ->nullable()
                    ->constrained('events', 'event_id')
                    ->cascadeOnUpdate()
                    ->cascadeOnDelete();
                $table->string('question_text');
                $table->enum('question_type', ['rating', 'text'])->default('rating');
                $table->unsignedSmallInteger('sort_order')->default(0);
                $table->boolean('is_required')->default(true);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        } elseif (! Schema::hasColumn('evaluation_questions', 'is_active')) {
            Schema::table('evaluation_questions', function (Blueprint $table) {
                $table->boolean('is_active')->default(true)->after('is_required');
            });
        }

        if (! Schema::hasTable('evaluation_answers')) {
            Schema::create('evaluation_answers', function (Blueprint $table) {
                $table->id('answer_id');
                $table->foreignId('evaluation_id')
                    ->constrained('evaluations', 'evaluation_id')
                    ->cascadeOnUpdate()
                    ->cascadeOnDelete();
                $table->foreignId('question_id')
                    ->constrained('evaluation_questions', 'question_id')
                    ->cascadeOnUpdate()
                    ->cascadeOnDelete();
                $table->unsignedTinyInteger('rating_value')->nullable();
                $table->text('answer_text')->nullable();
                $table->timestamp('created_at')->useCurrent();

                $table->unique(['evaluation_id', 'question_id'], 'evaluation_answers_eval_question_unique');
            });
        } elseif (! Schema::hasColumn('evaluation_answers', 'answer_text')) {
            Schema::table('evaluation_answers', function (Blueprint $table) {
                $table->text('answer_text')->nullable()->after('rating_value');
            });
        }

        $this->seedDefaultQuestions();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluation_answers');
        Schema::dropIfExists('evaluation_questions');

        Schema::table('registrations', function (Blueprint $table) {
            if (Schema::hasColumn('registrations', 'evaluation_submitted_at')) {
                $table->dropColumn('evaluation_submitted_at');
            }

            if (Schema::hasColumn('registrations', 'evaluation_token')) {
                $table->dropColumn('evaluation_token');
            }
        });

        if (Schema::hasTable('evaluations') && Schema::hasColumn('evaluations', 'evaluator_id')) {
            Schema::table('evaluations', function (Blueprint $table) {
                $table->dropForeign(['evaluator_id']);
            });

            Schema::table('evaluations', function (Blueprint $table) {
                $table->foreignId('evaluator_id')->nullable(false)->change();
                $table->foreign('evaluator_id')
                    ->references('id')
                    ->on('users')
                    ->cascadeOnUpdate()
                    ->cascadeOnDelete();
            });
        }
    }

    private function seedDefaultQuestions(): void
    {
        if (! Schema::hasTable('evaluation_questions')) {
            return;
        }

        $hasDefaults = DB::table('evaluation_questions')
            ->whereNull('event_id')
            ->exists();

        if ($hasDefaults) {
            return;
        }

        $now = now();
        $defaults = [
            [
                'event_id' => null,
                'question_text' => 'How would you rate your overall experience at this event?',
                'question_type' => 'rating',
                'sort_order' => 1,
                'is_required' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'event_id' => null,
                'question_text' => 'How would you rate the quality of organization and logistics?',
                'question_type' => 'rating',
                'sort_order' => 2,
                'is_required' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'event_id' => null,
                'question_text' => 'How relevant and valuable was the event content for you?',
                'question_type' => 'rating',
                'sort_order' => 3,
                'is_required' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        if (Schema::hasColumn('evaluation_questions', 'is_active')) {
            foreach ($defaults as &$row) {
                $row['is_active'] = true;
            }
            unset($row);
        }

        DB::table('evaluation_questions')->insert($defaults);
    }
};
