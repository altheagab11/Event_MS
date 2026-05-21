<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventRegistrant;
use App\Models\RegistrationVerificationCode;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class EventRegistrantController extends Controller
{
    /**
     * Persist a verified public registration: event_registrants row plus linked registrations / papers.
     *
     * @return array<string, mixed>
     */
    public function completeVerifiedEnrollment(
        RegistrationVerificationCode $verification,
        array $payload,
        Event $event,
        bool $isConferenceEvent,
    ): array {
        return DB::transaction(function () use ($verification, $payload, $event, $isConferenceEvent): array {
            $email = strtolower(trim((string) ($payload['email'] ?? '')));

            $activeEventRegistrant = DB::table('event_registrants')
                ->where('event_id', $event->event_id)
                ->whereRaw('LOWER(email) = ?', [$email])
                ->whereIn('status', ['pending', 'approved'])
                ->lockForUpdate()
                ->exists();

            if ($activeEventRegistrant) {
                throw ValidationException::withMessages([
                    'email' => 'Email address already registered for this event.',
                ]);
            }

            $legacyActiveRegistration = DB::table('registrations')
                ->join('users', 'users.id', '=', 'registrations.user_id')
                ->where('registrations.event_id', $event->event_id)
                ->whereRaw('LOWER(users.email) = ?', [$email])
                ->whereNotNull('registrations.user_id')
                ->whereNotIn('registrations.status', ['cancelled', 'rejected'])
                ->lockForUpdate()
                ->exists();

            if ($legacyActiveRegistration) {
                throw ValidationException::withMessages([
                    'email' => 'Email address already registered for this event.',
                ]);
            }

            $schoolUniversity = trim((string) ($payload['school_university'] ?? $payload['region'] ?? ''));
            $userType = trim((string) ($payload['user_type'] ?? $payload['school_from'] ?? ''));
            $participantRole = trim((string) ($payload['participant_role'] ?? $payload['school_level'] ?? ''));
            $attendanceMode = trim((string) ($payload['attendance_mode'] ?? ''));
            if ($attendanceMode === '') {
                $attendanceMode = $event->resolveRegistrationAttendanceMode(null);
            }

            $registrant = EventRegistrant::query()->create([
                'event_id' => $event->event_id,
                'first_name' => (string) ($payload['first_name'] ?? ''),
                'last_name' => (string) ($payload['last_name'] ?? ''),
                'email' => $email,
                'school_university' => $schoolUniversity,
                'user_type' => $userType,
                'participant_role' => $participantRole,
                'status' => 'pending',
                'registration_date' => now(),
            ]);

            $registrationRow = [
                'user_id' => null,
                'event_registrant_id' => $registrant->event_registrant_id,
                'event_id' => $event->event_id,
                'registration_date' => now(),
                'status' => 'pending',
            ];

            if (Schema::hasColumn('registrations', 'attendance_mode')) {
                $registrationRow['attendance_mode'] = $attendanceMode;
            }

            $registrationId = DB::table('registrations')->insertGetId(
                $registrationRow,
                'registration_id',
            );

            $requiresPaper = $isConferenceEvent && strcasecmp($participantRole, 'Presentor') === 0;

            if ($requiresPaper) {
                $finalPaperPath = $this->movePendingPaperToFinalPath(
                    (string) $verification->paper_temp_path,
                    $event->event_id,
                    $registrant->event_registrant_id,
                );

                if ($finalPaperPath === null) {
                    throw ValidationException::withMessages([
                        'code' => 'Unable to finalize the uploaded research paper. Please register again.',
                    ]);
                }

                DB::table('papers')->insert([
                    'user_id' => null,
                    'event_registrant_id' => $registrant->event_registrant_id,
                    'event_id' => $event->event_id,
                    'title' => $event->event_name.' Submission - '.$registrant->first_name.' '.$registrant->last_name,
                    'file_path' => $finalPaperPath,
                    'status' => 'submitted',
                    'created_at' => now(),
                ]);
            }

            $verification->forceFill([
                'status' => 'verified',
                'verified_at' => now(),
            ])->save();

            return [
                'registration_id' => $registrationId,
                'registration_status' => 'pending',
                'event_name' => $event->event_name,
                'event_date' => Carbon::parse($event->event_date)->format('F j, Y'),
                'location' => $event->location ?: 'TBA',
                'full_name' => trim($registrant->first_name.' '.$registrant->last_name),
                'email' => $registrant->email,
                'school_level' => $participantRole !== '' ? $participantRole : 'Participant',
                'region' => $schoolUniversity,
                'school_from' => $userType,
                'attendance_mode' => $attendanceMode,
            ];
        });
    }

    private function movePendingPaperToFinalPath(string $pendingPath, int $eventId, int $eventRegistrantId): ?string
    {
        $publicDisk = Storage::disk('public');
        $localDisk = Storage::disk('local');

        $filename = pathinfo($pendingPath, PATHINFO_BASENAME);
        $finalPath = 'papers/event-'.$eventId.'/registrant-'.$eventRegistrantId.'-'.$filename;

        if ($publicDisk->exists($pendingPath)) {
            $publicDisk->move($pendingPath, $finalPath);

            return $finalPath;
        }

        if (! $localDisk->exists($pendingPath)) {
            return null;
        }

        $stream = $localDisk->readStream($pendingPath);
        if ($stream === false) {
            return null;
        }

        $publicDisk->put($finalPath, $stream);
        if (is_resource($stream)) {
            fclose($stream);
        }

        $localDisk->delete($pendingPath);

        return $finalPath;
    }
}
