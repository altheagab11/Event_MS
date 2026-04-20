<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Evaluation;
use App\Models\Event;
use App\Models\Paper;
use App\Models\Registration;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
  public function index(Request $request)
  {
    $totalParticipants = Registration::query()
      ->whereHas('user', fn($q) => $q->where('role', 'participant'))
      ->count();

    $pendingPapers = Paper::query()
      ->whereIn('status', ['submitted', 'under_review'])
      ->count();

    $activeEvents = Event::query()
      ->where(function ($query) {
        $query->whereNull('status')
          ->orWhere('status', '!=', 'archived');
      })
      ->whereDate('event_date', '>=', now()->startOfDay())
      ->count();

    $totalCheckedIn = Attendance::query()
      ->whereNotNull('check_in_time')
      ->count();

    $papersSubmitted = Paper::query()->count();

    $evaluationsCount = Evaluation::query()->count();
    $avgScore = (float) number_format((float) (Evaluation::query()->avg('score') ?? 0), 1);

    return view('admin.dashboard', compact(
      'totalParticipants',
      'pendingPapers',
      'activeEvents',
      'totalCheckedIn',
      'papersSubmitted',
      'evaluationsCount',
      'avgScore'
    ));
  }
}
