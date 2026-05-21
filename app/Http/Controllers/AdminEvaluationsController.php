<?php

namespace App\Http\Controllers;

use App\Models\Evaluation;
use App\Models\EvaluationAnswer;
use App\Services\EventEvaluationService;
use Illuminate\Http\JsonResponse;

class AdminEvaluationsController extends Controller
{
    public function index(EventEvaluationService $evaluationService)
    {
        $rows = Evaluation::query()
            ->with([
                'event:event_id,event_name',
                'registration.eventRegistrant:event_registrant_id,first_name,last_name,email',
                'registration.user:id,firstname,lastname,email',
            ])
            ->whereNotNull('registration_id')
            ->orderByDesc('evaluated_at')
            ->get();

        $evaluations = $rows->map(fn (Evaluation $evaluation): array => $this->mapEvaluationCard($evaluation, $evaluationService));

        $averageRating = $evaluations->isEmpty()
            ? null
            : round($evaluations->avg(fn (array $e): float => $e['score_numeric']), 1);

        return view('admin.evaluations', [
            'evaluations' => $evaluations,
            'averageRating' => $averageRating,
        ]);
    }

    public function show(Evaluation $evaluation, EventEvaluationService $evaluationService): JsonResponse
    {
        if ($evaluation->registration_id === null) {
            return response()->json(['message' => 'Evaluation not found.'], 404);
        }

        $evaluation->load([
            'event:event_id,event_name',
            'registration.eventRegistrant:event_registrant_id,first_name,last_name,email',
            'registration.user:id,firstname,lastname,email',
        ]);

        $card = $this->mapEvaluationCard($evaluation, $evaluationService);

        $answers = EvaluationAnswer::query()
            ->join('evaluation_questions as eq', 'eq.question_id', '=', 'evaluation_answers.question_id')
            ->where('evaluation_answers.evaluation_id', $evaluation->evaluation_id)
            ->orderBy('eq.sort_order')
            ->orderBy('eq.question_id')
            ->get([
                'evaluation_answers.answer_id',
                'evaluation_answers.rating_value',
                'evaluation_answers.answer_text',
                'eq.question_id',
                'eq.question_text',
                'eq.question_type',
                'eq.sort_order',
            ])
            ->map(fn ($row): array => [
                'question_id' => (int) $row->question_id,
                'question_text' => (string) $row->question_text,
                'question_type' => (string) $row->question_type,
                'rating_value' => $row->rating_value !== null ? (int) $row->rating_value : null,
                'answer_text' => trim((string) ($row->answer_text ?? '')),
                'display_value' => $this->formatAnswerDisplay($row),
            ])
            ->values();

        return response()->json([
            'evaluation' => $card,
            'answers' => $answers,
        ]);
    }

    private function mapEvaluationCard(Evaluation $evaluation, EventEvaluationService $evaluationService): array
    {
        $registration = $evaluation->registration;
        $participantName = $registration !== null
            ? $evaluationService->resolveParticipantName($registration)
            : 'Participant';

        $participantEmail = trim((string) ($evaluation->participant_email ?? ''));
        if ($participantEmail === '' && $registration !== null) {
            $participantEmail = $evaluationService->resolveParticipantEmail($registration);
        }

        $eventName = $evaluation->event?->event_name ?? 'Unknown Event';
        $fullComment = trim((string) ($evaluation->comment ?? ''));
        $preview = $fullComment === ''
            ? 'No comment provided.'
            : (mb_strlen($fullComment) > 120 ? mb_substr($fullComment, 0, 120).'...' : $fullComment);

        $score = (float) $evaluation->score;
        $scoreDisplay = fmod($score, 1.0) === 0.0
            ? (string) (int) $score
            : number_format($score, 1);

        $rating = (int) max(1, min(5, (int) round($score)));

        $evaluatedAt = $evaluation->evaluated_at;

        return [
            'id' => (int) $evaluation->evaluation_id,
            'participant_name' => $participantName,
            'participant_email' => $participantEmail !== '' ? $participantEmail : '—',
            'avatar' => strtoupper(mb_substr(trim($participantName), 0, 1)),
            'date' => $evaluatedAt?->format('M j, Y')
                ?? date('M j, Y', strtotime((string) $evaluatedAt)),
            'evaluated_at' => $evaluatedAt?->toIso8601String(),
            'score' => $scoreDisplay,
            'score_numeric' => $score,
            'rating' => $rating,
            'event_name' => strtoupper((string) $eventName),
            'comment_preview' => $preview,
            'comment_full' => $fullComment,
        ];
    }

    private function formatAnswerDisplay(object $row): string
    {
        $type = (string) ($row->question_type ?? 'rating');

        if ($type === 'text') {
            $text = trim((string) ($row->answer_text ?? ''));

            return $text !== '' ? $text : 'Not provided';
        }

        $rating = $row->rating_value !== null ? (int) $row->rating_value : 0;

        return $rating >= 1 && $rating <= 5 ? $rating.'/5' : 'Not provided';
    }
}
