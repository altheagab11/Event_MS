<?php

namespace App\Http\Controllers;

use App\Models\Evaluation;
use App\Models\Event;
use App\Models\Paper;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class AdminDashboardController extends Controller
{
    public function index(Request $request)
    {
        $participantRegistrations = Registration::query();

        if (Schema::hasColumn('registrations', 'event_registrant_id')) {
            $participantRegistrations->where(function ($query) {
                $query->whereHas('user', fn ($userQuery) => $userQuery->where('role', 'participant'))
                    ->orWhereNotNull('event_registrant_id');
            });
        } else {
            $participantRegistrations->whereHas('user', fn ($userQuery) => $userQuery->where('role', 'participant'));
        }

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

        $activeEventsQuery = Event::query();

        if (Schema::hasColumn('events', 'status')) {
            $activeEventsQuery->where(function ($query) {
                $query->whereNull('status')
                    ->orWhere('status', '!=', 'archived');
            });
        }

        $activeEvents = $activeEventsQuery
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
