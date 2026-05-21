<?php

namespace App\Services;

use App\Models\Evaluation;
use App\Models\EvaluationAnswer;
use App\Models\EvaluationQuestion;
use App\Models\Event;
use App\Models\Registration;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class EventEvaluationService
{
    public const ERROR_INVALID_TOKEN = 'invalid_token';

    public const ERROR_NOT_APPROVED = 'not_approved';

    public const ERROR_NOT_ATTENDED = 'not_attended';

    public const ERROR_ALREADY_SUBMITTED = 'already_submitted';

    public const ERROR_NOT_OPEN = 'not_open';

    /**
     * @return array{
     *   valid: bool,
     *   error?: string,
     *   registration?: Registration,
     *   event?: Event,
     *   participant_name?: string,
     *   participant_email?: string,
     *   questions?: Collection<int, EvaluationQuestion>
     * }
     */
    public function resolveFormContext(string $token): array
    {
        $token = trim($token);
        if ($token === '' || ! Schema::hasColumn('registrations', 'evaluation_token')) {
            return ['valid' => false, 'error' => self::ERROR_INVALID_TOKEN];
        }

        $registration = Registration::query()
            ->with([
                'event',
                'user:id,firstname,lastname,email',
                'eventRegistrant:event_registrant_id,first_name,last_name,email',
            ])
            ->where('evaluation_token', $token)
            ->first();

        if ($registration === null || $registration->event === null) {
            return ['valid' => false, 'error' => self::ERROR_INVALID_TOKEN];
        }

        if ((string) $registration->status !== 'approved') {
            return ['valid' => false, 'error' => self::ERROR_NOT_APPROVED];
        }

        if (Schema::hasColumn('events', 'evaluation_links_sent_at')
            && $registration->event->evaluation_links_sent_at === null) {
            return ['valid' => false, 'error' => self::ERROR_NOT_OPEN];
        }

        $certificateService = app(PostEventCertificateService::class);
        if (! $certificateService->hasCheckedIn($registration)) {
            return ['valid' => false, 'error' => self::ERROR_NOT_ATTENDED];
        }

        if ($this->hasSubmittedEvaluation($registration)) {
            return ['valid' => false, 'error' => self::ERROR_ALREADY_SUBMITTED];
        }

        return [
            'valid' => true,
            'registration' => $registration,
            'event' => $registration->event,
            'participant_name' => $this->resolveParticipantName($registration),
            'participant_email' => $this->resolveParticipantEmail($registration),
            'questions' => $this->questionsForEvent((int) $registration->event_id),
        ];
    }

    /**
     * @param  array<int, int>  $ratings  question_id => rating (1-5)
     */
    public function submit(Registration $registration, array $ratings, ?string $comment): Evaluation
    {
        $event = $registration->event ?? Event::query()->findOrFail($registration->event_id);
        $questions = $this->questionsForEvent((int) $registration->event_id);
        $ratingQuestions = $questions->where('question_type', 'rating');

        $ratingValues = [];
        foreach ($ratingQuestions as $question) {
            $value = (int) ($ratings[(int) $question->question_id] ?? 0);
            if ($question->is_required && ($value < 1 || $value > 5)) {
                throw new \InvalidArgumentException('All required rating questions must be answered.');
            }

            if ($value >= 1 && $value <= 5) {
                $ratingValues[] = $value;
            }
        }

        if ($ratingValues === []) {
            throw new \InvalidArgumentException('At least one rating is required.');
        }

        $averageScore = round(array_sum($ratingValues) / count($ratingValues), 2);
        $participantEmail = $this->resolveParticipantEmail($registration);

        return DB::transaction(function () use ($registration, $event, $questions, $ratings, $comment, $averageScore, $participantEmail): Evaluation {
            $evaluation = Evaluation::query()->create([
                'paper_id' => null,
                'evaluator_id' => $registration->user_id,
                'registration_id' => $registration->registration_id,
                'event_id' => $event->event_id,
                'participant_email' => $participantEmail !== '' ? $participantEmail : null,
                'score' => $averageScore,
                'comment' => $comment !== null && trim($comment) !== '' ? trim($comment) : null,
                'evaluated_at' => now(),
            ]);

            foreach ($questions as $question) {
                if ($question->question_type !== 'rating') {
                    continue;
                }

                $ratingValue = (int) ($ratings[(int) $question->question_id] ?? 0);
                if ($ratingValue < 1 || $ratingValue > 5) {
                    continue;
                }

                EvaluationAnswer::query()->create([
                    'evaluation_id' => $evaluation->evaluation_id,
                    'question_id' => $question->question_id,
                    'rating_value' => $ratingValue,
                    'answer_text' => null,
                ]);
            }

            if (Schema::hasColumn('registrations', 'evaluation_submitted_at')) {
                Registration::query()
                    ->where('registration_id', $registration->registration_id)
                    ->update(['evaluation_submitted_at' => now()]);
            }

            return $evaluation;
        });
    }

    public function assignTokenIfMissing(Registration $registration): string
    {
        $existing = trim((string) ($registration->evaluation_token ?? ''));
        if ($existing !== '') {
            return $existing;
        }

        do {
            $token = Str::random(48);
        } while (Registration::query()->where('evaluation_token', $token)->exists());

        Registration::query()
            ->where('registration_id', $registration->registration_id)
            ->update(['evaluation_token' => $token]);

        $registration->evaluation_token = $token;

        return $token;
    }

    public function buildEvaluationUrl(Registration $registration): string
    {
        $token = $this->assignTokenIfMissing($registration);

        return route('events.evaluation.show', ['token' => $token]);
    }

    public function hasSubmittedEvaluation(Registration $registration): bool
    {
        if (Schema::hasColumn('registrations', 'evaluation_submitted_at')
            && $registration->evaluation_submitted_at !== null) {
            return true;
        }

        if (Schema::hasColumn('evaluations', 'event_id') && Schema::hasColumn('evaluations', 'registration_id')) {
            return Evaluation::query()
                ->where('event_id', $registration->event_id)
                ->where('registration_id', $registration->registration_id)
                ->exists();
        }

        return false;
    }

    /**
     * @return Collection<int, EvaluationQuestion>
     */
    public function questionsForEvent(int $eventId): Collection
    {
        if (! Schema::hasTable('evaluation_questions')) {
            return collect();
        }

        $eventQuestions = EvaluationQuestion::query()
            ->where('event_id', $eventId)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('question_id')
            ->get();

        if ($eventQuestions->isNotEmpty()) {
            return $eventQuestions;
        }

        return EvaluationQuestion::query()
            ->whereNull('event_id')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('question_id')
            ->get();
    }

    public function resolveParticipantName(Registration $registration): string
    {
        if ($registration->user !== null) {
            $name = trim((string) ($registration->user->firstname ?? '').' '.(string) ($registration->user->lastname ?? ''));

            return $name !== '' ? $name : 'Participant';
        }

        if ($registration->eventRegistrant !== null) {
            $name = trim((string) ($registration->eventRegistrant->first_name ?? '').' '.(string) ($registration->eventRegistrant->last_name ?? ''));

            return $name !== '' ? $name : 'Participant';
        }

        return 'Participant';
    }

    public function resolveParticipantEmail(Registration $registration): string
    {
        if ($registration->user !== null) {
            return strtolower(trim((string) ($registration->user->email ?? '')));
        }

        if ($registration->eventRegistrant !== null) {
            return strtolower(trim((string) ($registration->eventRegistrant->email ?? '')));
        }

        return '';
    }

    /**
     * @return array<string, string>
     */
    public function errorMessages(): array
    {
        return [
            self::ERROR_INVALID_TOKEN => 'This evaluation link is invalid or has expired.',
            self::ERROR_NOT_APPROVED => 'Only approved participants can submit an evaluation for this event.',
            self::ERROR_NOT_ATTENDED => 'Only participants who checked in at the event can submit an evaluation.',
            self::ERROR_ALREADY_SUBMITTED => 'You have already submitted an evaluation for this event.',
            self::ERROR_NOT_OPEN => 'Evaluation is not open yet for this event. Please wait for the organizer to send evaluation links.',
        ];
    }
}
