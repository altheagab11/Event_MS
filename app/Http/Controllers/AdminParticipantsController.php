<?php

namespace App\Http\Controllers;

use App\Models\Paper;
use App\Models\Registration;

class AdminParticipantsController extends Controller
{
  public function index()
  {
    $participants = Registration::query()
      ->whereHas('user', function ($query) {
        $query->where('role', 'participant');
      })
      ->with([
        'user:id,firstname,lastname,email',
        'event:event_id,event_name',
        'attendance:attendance_id,registration_id,check_in_time',
      ])
      ->select(['registration_id', 'user_id', 'event_id', 'status', 'registration_date'])
      ->addSelect([
        'latest_paper_status' => Paper::query()
          ->select('status')
          ->whereColumn('papers.user_id', 'registrations.user_id')
          ->whereColumn('papers.event_id', 'registrations.event_id')
          ->orderByDesc('paper_id')
          ->limit(1),
      ])
      ->orderByDesc('registration_date')
      ->get()
      ->map(function (Registration $registration): array {
        [$statusLabel, $statusClass] = $this->resolveDisplayStatus(
          (string) $registration->status,
          $registration->latest_paper_status,
          $registration->attendance?->check_in_time,
        );

        return [
          'name' => trim(($registration->user->firstname ?? '') . ' ' . ($registration->user->lastname ?? '')),
          'email' => (string) ($registration->user->email ?? ''),
          'event_name' => (string) ($registration->event->event_name ?? 'Unknown Event'),
          'status_label' => $statusLabel,
          'status_class' => $statusClass,
        ];
      });

    return view('admin.participants', [
      'participants' => $participants,
    ]);
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
