<?php

namespace App\Http\Controllers;

use App\Models\Evaluation;
use App\Models\Event;
use App\Models\Paper;
use App\Models\Registration;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index(Request $request)
    {
        $participantRegistrations = Registration::query()
            ->where(function ($query) {
                $query->whereHas('user', fn ($userQuery) => $userQuery->where('role', 'participant'))
                    ->orWhereNotNull('event_registrant_id');
            });

        $totalParticipants = (clone $participantRegistrations)->count();

        $pendingParticipants = (clone $participantRegistrations)
            ->where('status', 'pending')
            ->count();

        $pendingPapers = Paper::query()
            ->whereIn('status', ['submitted', 'under_review'])
            ->count();

        $approvedPapers = Paper::query()
            ->where('status', 'accepted')
            ->count();

        $activeEvents = Event::query()
            ->where(function ($query) {
                $query->whereNull('status')
                    ->orWhere('status', '!=', 'archived');
            })
            ->whereDate('event_date', '>=', now()->startOfDay())
            ->count();

        $papersSubmitted = Paper::query()->count();

        $evaluationsCount = Evaluation::query()->count();
        $avgScore = (float) number_format((float) (Evaluation::query()->avg('score') ?? 0), 1);

        return view('admin.dashboard', compact(
            'activeEvents',
            'totalParticipants',
            'pendingParticipants',
            'approvedPapers',
            'pendingPapers',
            'papersSubmitted',
            'evaluationsCount',
            'avgScore'
        ));
    }
}
