<?php

namespace App\Http\Controllers;

use App\Mail\DigitalPassPreviewMail;
use App\Models\Paper;
use App\Models\Registration;
use App\Models\RegistrationVerificationCode;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Throwable;

class AdminParticipantsController extends Controller
{
  public function index()
  {
    $registrations = Registration::query()
      ->whereHas('user', function ($query) {
        $query->where('role', 'participant');
      })
      ->with([
        'user:id,firstname,lastname,email',
        'event:event_id,event_name,event_type',
        'attendance:attendance_id,registration_id,check_in_time',
      ])
      ->select(['registration_id', 'user_id', 'event_id', 'status', 'registration_date'])
      ->orderByDesc('registration_date')
      ->get();

    $userIds = $registrations->pluck('user_id')->unique()->values();
    $eventIds = $registrations->pluck('event_id')->unique()->values();
    $emails = $registrations
      ->map(fn (Registration $registration): string => strtolower((string) ($registration->user->email ?? '')))
      ->filter(fn (string $email): bool => $email !== '')
      ->unique()
      ->values();

    $latestPapers = Paper::query()
      ->whereIn('user_id', $userIds)
      ->whereIn('event_id', $eventIds)
      ->orderByDesc('paper_id')
      ->get()
      ->groupBy(fn (Paper $paper): string => $paper->user_id . '-' . $paper->event_id)
      ->map(fn ($group): ?Paper => $group->first());

    $verificationProfiles = RegistrationVerificationCode::query()
      ->where('status', 'verified')
      ->whereIn('email', $emails)
      ->whereIn('event_id', $eventIds)
      ->orderByDesc('id')
      ->get()
      ->groupBy(fn (RegistrationVerificationCode $verification): string => strtolower($verification->email) . '-' . $verification->event_id)
      ->map(fn ($group): ?RegistrationVerificationCode => $group->first());

    $participants = $registrations
      ->map(function (Registration $registration) use ($latestPapers, $verificationProfiles): array {
        $paperKey = $registration->user_id . '-' . $registration->event_id;
        $profileKey = strtolower((string) ($registration->user->email ?? '')) . '-' . $registration->event_id;

        /** @var ?\App\Models\Paper $latestPaper */
        $latestPaper = $latestPapers->get($paperKey);
        /** @var ?\App\Models\RegistrationVerificationCode $verification */
        $verification = $verificationProfiles->get($profileKey);

        $payload = (array) ($verification?->payload ?? []);
        $schoolFrom = trim((string) ($payload['school_from'] ?? ''));
        $schoolLevel = trim((string) ($payload['school_level'] ?? ''));
        $region = trim((string) ($payload['region'] ?? ''));
        $levelRegion = $schoolLevel !== '' && $region !== ''
          ? $schoolLevel . ' / ' . $region
          : ($schoolLevel !== '' ? $schoolLevel : ($region !== '' ? $region : 'Not provided'));

        $paperDetails = $this->buildPaperDetails($registration, $latestPaper);

        [$statusLabel, $statusClass] = $this->resolveDisplayStatus(
          (string) $registration->status,
          $latestPaper?->status,
          $registration->attendance?->check_in_time,
        );

        return [
          'registration_id' => (int) $registration->registration_id,
          'registration_status' => (string) $registration->status,
          'name' => trim(($registration->user->firstname ?? '') . ' ' . ($registration->user->lastname ?? '')),
          'email' => (string) ($registration->user->email ?? ''),
          'event_name' => (string) ($registration->event->event_name ?? 'Unknown Event'),
          'event_type' => (string) ($registration->event->event_type ?? 'School Event'),
          'status_label' => $statusLabel,
          'status_class' => $statusClass,
          'school_affiliation' => $schoolFrom !== '' ? $schoolFrom : 'Not provided',
          'level_region' => $levelRegion,
          'paper' => $paperDetails,
          'approve_url' => route('admin.participants.approve', ['registration' => $registration->registration_id]),
          'reject_url' => route('admin.participants.reject', ['registration' => $registration->registration_id]),
        ];
      });

    return view('admin.participants', [
      'participants' => $participants,
    ]);
  }

  public function downloadLatestPaper(Registration $registration): BinaryFileResponse
  {
    $paper = Paper::query()
      ->where('user_id', $registration->user_id)
      ->where('event_id', $registration->event_id)
      ->orderByDesc('paper_id')
      ->first();

    if ($paper === null || trim((string) $paper->file_path) === '') {
      abort(404, 'No uploaded paper found for this participant.');
    }

    $path = trim((string) $paper->file_path);
    $absolutePath = $this->resolveStoredFileAbsolutePath($path);

    if ($absolutePath === null) {
      abort(404, 'Uploaded paper file does not exist.');
    }

    return response()->download($absolutePath, basename($path));
  }

  public function rejectApplication(Registration $registration): RedirectResponse
  {
    $registration->loadMissing(['event:event_id,event_type']);
    $isConference = (string) ($registration->event->event_type ?? '') === 'Conference';

    DB::transaction(function () use ($registration, $isConference): void {
      Registration::query()
        ->where('registration_id', $registration->registration_id)
        ->update(['status' => 'rejected']);

      if (! $isConference) {
        return;
      }

      $latestPaper = Paper::query()
        ->where('user_id', $registration->user_id)
        ->where('event_id', $registration->event_id)
        ->orderByDesc('paper_id')
        ->lockForUpdate()
        ->first();

      if ($latestPaper !== null && $latestPaper->status !== 'rejected') {
        $latestPaper->forceFill(['status' => 'rejected'])->save();
      }
    });

    return redirect()
      ->route('admin.participants')
      ->with('status_type', 'success')
      ->with('status_message', 'Application rejected successfully.');
  }

  public function approveAndSendId(Registration $registration): RedirectResponse
  {
    $registration->loadMissing([
      'user:id,firstname,lastname,email',
      'event:event_id,event_name,event_type,event_date,location',
    ]);

    $isConference = (string) ($registration->event->event_type ?? '') === 'Conference';

    $result = DB::transaction(function () use ($registration, $isConference): array {
      Registration::query()
        ->where('registration_id', $registration->registration_id)
        ->update(['status' => 'approved']);

      if ($isConference) {
        $latestPaper = Paper::query()
          ->where('user_id', $registration->user_id)
          ->where('event_id', $registration->event_id)
          ->orderByDesc('paper_id')
          ->lockForUpdate()
          ->first();

        if ($latestPaper !== null && in_array($latestPaper->status, ['submitted', 'under_review'], true)) {
          $latestPaper->forceFill(['status' => 'accepted'])->save();
        }
      }

      $digitalId = DB::table('digital_ids')
        ->where('user_id', $registration->user_id)
        ->where('event_id', $registration->event_id)
        ->lockForUpdate()
        ->first();

      if ($digitalId === null) {
        $passCode = $this->generateUniquePassCode();
        DB::table('digital_ids')->insert([
          'user_id' => $registration->user_id,
          'event_id' => $registration->event_id,
          'qr_code' => $passCode,
          'issued_at' => now(),
        ]);
      } else {
        $passCode = (string) $digitalId->qr_code;
      }

      return [
        'pass_code' => $passCode,
      ];
    });

    $passData = [
      'event_name' => (string) ($registration->event->event_name ?? 'Event'),
      'event_date' => $this->formatEventDate($registration->event->event_date ?? null),
      'location' => (string) ($registration->event->location ?? 'TBA'),
      'full_name' => trim((string) ($registration->user->firstname ?? '') . ' ' . (string) ($registration->user->lastname ?? '')),
      'email' => (string) ($registration->user->email ?? ''),
      'school_level' => 'Participant',
      'pass_code' => (string) $result['pass_code'],
    ];

    $mailSent = true;
    try {
      Mail::to($passData['email'])->send(new DigitalPassPreviewMail($passData));
    } catch (Throwable $exception) {
      $mailSent = false;
      Log::error('Approve & Send ID email failed.', [
        'registration_id' => $registration->registration_id,
        'email' => $passData['email'],
        'error' => $exception->getMessage(),
      ]);
    }

    if (! $mailSent) {
      return redirect()
        ->route('admin.participants')
        ->with('status_type', 'warning')
        ->with('status_message', 'Application approved, but sending the digital ID email failed. Please check mail configuration.');
    }

    return redirect()
      ->route('admin.participants')
      ->with('status_type', 'success')
      ->with('status_message', 'Application approved and digital ID email sent.');
  }

  private function buildPaperDetails(Registration $registration, ?Paper $paper): array
  {
    if ($paper === null || trim((string) $paper->file_path) === '') {
      return [
        'has_file' => false,
        'status' => null,
        'title' => null,
        'file_name' => null,
        'file_type' => null,
        'file_size' => null,
        'submitted_at' => null,
        'download_url' => null,
      ];
    }

    $path = trim((string) $paper->file_path);
    $absolutePath = $this->resolveStoredFileAbsolutePath($path);
    $exists = $absolutePath !== null;

    $fileSize = null;
    $mimeType = null;
    if ($exists) {
      $sizeInBytes = filesize($absolutePath);
      $mimeType = function_exists('mime_content_type') ? (mime_content_type($absolutePath) ?: null) : null;
      $fileSize = $this->formatFileSize($sizeInBytes);
    }

    return [
      'has_file' => $exists,
      'status' => (string) $paper->status,
      'title' => (string) $paper->title,
      'file_name' => basename($path),
      'file_type' => $this->formatFileType($path, $mimeType),
      'file_size' => $fileSize,
      'submitted_at' => $paper->created_at?->format('M d, Y h:i A'),
      'download_url' => $exists
        ? route('admin.participants.paper.download', ['registration' => $registration->registration_id])
        : null,
    ];
  }

  private function formatFileSize(?int $bytes): ?string
  {
    if ($bytes === null || $bytes < 0) {
      return null;
    }

    if ($bytes < 1024) {
      return $bytes . ' B';
    }

    $kb = $bytes / 1024;
    if ($kb < 1024) {
      return number_format($kb, 2) . ' KB';
    }

    $mb = $kb / 1024;
    return number_format($mb, 2) . ' MB';
  }

  private function formatFileType(string $path, ?string $mimeType): string
  {
    if ($mimeType !== null && $mimeType !== '') {
      return $mimeType;
    }

    $extension = strtoupper((string) pathinfo($path, PATHINFO_EXTENSION));
    return $extension !== '' ? $extension : 'Unknown';
  }

  private function resolveStoredFileAbsolutePath(string $path): ?string
  {
    $relativePath = ltrim($path, '/');
    $publicPath = storage_path('app/public/' . $relativePath);
    if (is_file($publicPath)) {
      return $publicPath;
    }

    $localPath = storage_path('app/' . $relativePath);
    if (is_file($localPath)) {
      return $localPath;
    }

    return null;
  }

  private function formatEventDate(mixed $eventDate): string
  {
    if ($eventDate === null || (string) $eventDate === '') {
      return 'TBA';
    }

    try {
      return Carbon::parse((string) $eventDate)->format('F j, Y');
    } catch (Throwable) {
      return (string) $eventDate;
    }
  }

  private function generateUniquePassCode(): string
  {
    do {
      $code = 'NUL-' . strtoupper(Str::random(10));
      $exists = DB::table('digital_ids')->where('qr_code', $code)->exists();
    } while ($exists);

    return $code;
  }

  private function resolveDisplayStatus(string $registrationStatus, ?string $paperStatus, mixed $checkInTime): array
  {
    if ($checkInTime !== null) {
      return ['Checked-in', 'green'];
    }

    if (in_array($paperStatus, ['submitted', 'under_review'], true)) {
      return ['Pending Paper', 'gold'];
    }

    if ($registrationStatus === 'approved') {
      return ['Registered', 'blue'];
    }

    if ($registrationStatus === 'pending') {
      return ['Pending Approval', 'gold'];
    }

    if ($registrationStatus === 'rejected') {
      return ['Rejected', 'gold'];
    }

    if ($registrationStatus === 'cancelled') {
      return ['Cancelled', 'gold'];
    }

    return ['Unknown', 'gold'];
  }
}
