<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasTable('evaluation_questions')) {
            return;
        }

        $globalQuestions = DB::table('evaluation_questions')
            ->whereNull('event_id')
            ->orderBy('sort_order')
            ->orderBy('question_id')
            ->get();

        if ($globalQuestions->isNotEmpty() && ! $this->isLegacyDefaultSet($globalQuestions)) {
            return;
        }

        $defaults = [
            'The content was relevant to my needs.',
            'The material presented was clear and well-organized.',
            'The seminar addressed key issues affecting the community.',
            'The speaker(s) were knowledgeable and engaging.',
            'The seminar was interactive and encouraged participation.',
            'The pace of the presentation was appropriate.',
            'The seminar location was convenient.',
            'The seminar was well-organized.',
            'The seminar materials (handouts, slides) were useful.',
            'I am satisfied with the seminar overall.',
            'I would recommend this seminar to others.',
            'I gained valuable insights that I can apply in my community.',
        ];

        $now = now();
        $hasIsActive = Schema::hasColumn('evaluation_questions', 'is_active');
        $existing = $globalQuestions->values();

        foreach ($defaults as $index => $questionText) {
            $row = [
                'question_text' => $questionText,
                'question_type' => 'rating',
                'sort_order' => $index + 1,
                'is_required' => true,
                'updated_at' => $now,
            ];

            if ($hasIsActive) {
                $row['is_active'] = true;
            }

            if (isset($existing[$index])) {
                DB::table('evaluation_questions')
                    ->where('question_id', $existing[$index]->question_id)
                    ->update($row);
                continue;
            }

            $row['event_id'] = null;
            $row['created_at'] = $now;
            DB::table('evaluation_questions')->insert($row);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Keep current default questions as-is.
    }

    private function isLegacyDefaultSet(Collection $questions): bool
    {
        $legacy = [
            'How would you rate your overall experience at this event?',
            'How would you rate the quality of organization and logistics?',
            'How relevant and valuable was the event content for you?',
        ];

        if ($questions->count() !== count($legacy)) {
            return false;
        }

        foreach ($legacy as $index => $text) {
            if (trim((string) ($questions[$index]->question_text ?? '')) !== $text) {
                return false;
            }
        }

        return true;
    }
};
