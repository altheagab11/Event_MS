<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEventEvaluationRequest;
use App\Models\Evaluation;
use App\Models\Event;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;

class EventEvaluationController extends Controller
{
  public function store(StoreEventEvaluationRequest $request, Event $event): JsonResponse
  {
    $email = strtolower(trim((string) $request->input('email')));

    $participant = User::query()
      ->whereRaw('LOWER(email) = ?', [$email])
      ->where('role', 'participant')
      ->first();

    if ($participant === null) {
      throw ValidationException::withMessages([
        'email' => 'This email is not a registered participant for the selected event.',
      ]);
    }

    $registration = Registration::query()
      ->where('user_id', $participant->id)
      ->where('event_id', $event->event_id)
      ->whereIn('status', ['approved', 'pending'])
      ->orderByDesc('registration_id')
      ->first();

    if ($registration === null) {
      throw ValidationException::withMessages([
        'email' => 'This email is not linked to a valid registration for this event.',
      ]);
    }

    $hasEventId = Schema::hasColumn('evaluations', 'event_id');
    $hasRegistrationId = Schema::hasColumn('evaluations', 'registration_id');
    $hasParticipantEmail = Schema::hasColumn('evaluations', 'participant_email');

    $eventPaperId = DB::table('papers')
      ->where('user_id', $participant->id)
      ->where('event_id', $event->event_id)
      ->orderByDesc('paper_id')
      ->value('paper_id');

    if (! $hasEventId || ! $hasRegistrationId) {
      if ($eventPaperId === null) {
        throw ValidationException::withMessages([
          'email' => 'Evaluation storage is using the legacy schema and requires a submitted paper for this event. Please apply the latest evaluations migration to support event-based evaluations.',
        ]);
      }

      $alreadyEvaluated = Evaluation::query()
        ->where('evaluator_id', $participant->id)
        ->where('paper_id', $eventPaperId)
        ->exists();

      if ($alreadyEvaluated) {
        throw ValidationException::withMessages([
          'email' => 'An evaluation for this event has already been submitted using this participant account.',
        ]);
      }

      DB::transaction(function () use ($request, $participant, $eventPaperId): void {
        Evaluation::query()->create([
          'paper_id' => $eventPaperId,
          'evaluator_id' => $participant->id,
          'score' => (int) $request->integer('rating'),
          'comment' => trim((string) $request->input('comment', '')) ?: null,
          'evaluated_at' => now(),
        ]);
      });

      return response()->json([
        'message' => 'Evaluation submitted successfully.',
      ]);
    }

    $alreadyEvaluated = Evaluation::query()
      ->where('event_id', $event->event_id)
      ->where('registration_id', $registration->registration_id)
      ->exists();

    if ($alreadyEvaluated) {
      throw ValidationException::withMessages([
        'email' => 'An evaluation for this event has already been submitted using this participant account.',
      ]);
    }

    DB::transaction(function () use ($request, $participant, $registration, $event, $email, $hasParticipantEmail, $eventPaperId): void {
      $payload = [
        'paper_id' => $eventPaperId,
        'evaluator_id' => $participant->id,
        'registration_id' => $registration->registration_id,
        'event_id' => $event->event_id,
        'score' => (int) $request->integer('rating'),
        'comment' => trim((string) $request->input('comment', '')) ?: null,
        'evaluated_at' => now(),
      ];

      if ($hasParticipantEmail) {
        $payload['participant_email'] = $email;
      }

      Evaluation::query()->create($payload);
    });

    return response()->json([
      'message' => 'Evaluation submitted successfully.',
    ]);
  }
}
