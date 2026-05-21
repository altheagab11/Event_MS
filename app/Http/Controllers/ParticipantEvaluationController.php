<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubmitParticipantEvaluationRequest;
use App\Models\Registration;
use App\Services\EventEvaluationService;
use App\Services\PostEventCertificateService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ParticipantEvaluationController extends Controller
{
    public function show(string $token, EventEvaluationService $evaluationService): View
    {
        $context = $evaluationService->resolveFormContext($token);

        if (! ($context['valid'] ?? false)) {
            return view('evaluation.invalid', [
                'message' => $evaluationService->errorMessages()[(string) ($context['error'] ?? '')]
                    ?? 'This evaluation link is not available.',
            ]);
        }

        /** @var Registration $registration */
        $registration = $context['registration'];
        $event = $context['event'];

        return view('evaluation.form', [
            'token' => $token,
            'eventName' => (string) $event->event_name,
            'eventDate' => $event->formatted_schedule_range,
            'participantName' => (string) $context['participant_name'],
            'questions' => $context['questions'],
            'submitUrl' => route('events.evaluation.store', ['token' => $token]),
            'registrationId' => (int) $registration->registration_id,
        ]);
    }

    public function store(
        SubmitParticipantEvaluationRequest $request,
        string $token,
        EventEvaluationService $evaluationService,
        PostEventCertificateService $certificateService,
    ): RedirectResponse|View {
        $context = $evaluationService->resolveFormContext($token);

        if (! ($context['valid'] ?? false)) {
            return view('evaluation.invalid', [
                'message' => $evaluationService->errorMessages()[(string) ($context['error'] ?? '')]
                    ?? 'This evaluation link is not available.',
            ]);
        }

        /** @var Registration $registration */
        $registration = $context['registration'];
        $event = $context['event'];

        $ratings = [];
        foreach ((array) $request->input('ratings', []) as $questionId => $value) {
            $ratings[(int) $questionId] = (int) $value;
        }

        try {
            $evaluationService->submit(
                $registration,
                $ratings,
                $request->input('comment')
            );
        } catch (\InvalidArgumentException $exception) {
            return redirect()
                ->route('events.evaluation.show', ['token' => $token])
                ->withInput()
                ->withErrors(['form' => $exception->getMessage()]);
        }

        $registration->refresh();
        $certificateService->issueParticipationAfterEvaluation($registration, $event);

        return view('evaluation.success', [
            'eventName' => (string) $event->event_name,
            'participantName' => (string) $context['participant_name'],
        ]);
    }
}
