@php
    $registrationBadgeClasses = [
        'approved' => 'border-[#22C55E]/40 bg-[#22C55E]/15 text-[#86EFAC]',
        'pending' => 'border-[#FACC15]/40 bg-[#FACC15]/15 text-[#FACC15]',
        'rejected' => 'border-[#EF4444]/40 bg-[#EF4444]/15 text-[#FCA5A5]',
        'cancelled' => 'border-[#94A3B8]/30 bg-[#94A3B8]/10 text-[#CBD5E1]',
    ];
    $statusBadgeClass = match ($statusKey) {
        'archived' => 'bg-[#EF4444]',
        'done' => 'bg-[#64748B]',
        default => 'bg-[#22C55E]',
    };
@endphp

<section class="attendance-event-info">
    <div class="flex flex-wrap items-start justify-between gap-3">
        <div class="min-w-0 flex-1">
            <h3 class="text-lg font-black uppercase tracking-wide text-[#F8FAFC]">
                {{ $event->event_name }}
            </h3>
            <p class="mt-1 text-sm font-bold text-[#60A5FA]">{{ $eventTypeLabel }}</p>
        </div>
        <span class="shrink-0 rounded-xl {{ $statusBadgeClass }} px-3 py-1.5 text-xs font-black text-white">
            • {{ $statusLabel }}
        </span>
    </div>
    <div class="mt-4 grid grid-cols-1 gap-3 sm:grid-cols-3">
        <div>
            <p class="text-xs font-black uppercase tracking-widest text-[#94A3B8]">Start</p>
            <p class="mt-1 text-sm font-bold text-[#F8FAFC]">{{ $formattedStart }}</p>
        </div>
        <div>
            <p class="text-xs font-black uppercase tracking-widest text-[#94A3B8]">End</p>
            <p class="mt-1 text-sm font-bold text-[#F8FAFC]">{{ $formattedEnd }}</p>
        </div>
        <div>
            <p class="text-xs font-black uppercase tracking-widest text-[#94A3B8]">Venue</p>
            <p class="mt-1 text-sm font-bold text-[#F8FAFC]">{{ $event->location ?: 'Location TBA' }}</p>
        </div>
    </div>
</section>

<div class="attendance-summary">
    <div class="attendance-summary-card border border-[#60A5FA]/20 bg-[#13284A]/60">
        <p class="text-xs font-black uppercase tracking-widest text-[#94A3B8]">Total Registered</p>
        <p id="attendanceSummaryRegistered" class="mt-2 text-2xl font-black text-[#60A5FA]">{{ $totalRegistered }}</p>
    </div>
    <div class="attendance-summary-card border border-[#22C55E]/25 bg-[#22C55E]/10">
        <p class="text-xs font-black uppercase tracking-widest text-[#86EFAC]">Total Attended</p>
        <p id="attendanceSummaryAttended" class="mt-2 text-2xl font-black text-[#86EFAC]">{{ $totalAttended }}</p>
    </div>
    <div class="attendance-summary-card border border-[#FACC15]/25 bg-[#FACC15]/10">
        <p class="text-xs font-black uppercase tracking-widest text-[#FACC15]">Not Yet Attended</p>
        <p id="attendanceSummaryNotAttended" class="mt-2 text-2xl font-black text-[#FACC15]">{{ $totalNotAttended }}</p>
    </div>
</div>

<div class="attendance-controls">
    <div class="attendance-search">
        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m1.35-5.65a7 7 0 11-14 0 7 7 0 0114 0z"/>
        </svg>
        <input
            id="eventAttendanceSearchInput"
            type="search"
            placeholder="Search by name or email..."
            aria-label="Search participants"
        >
    </div>
    <div class="attendance-filter">
        <select id="eventAttendanceStatusFilter" aria-label="Filter by attendance status">
            <option value="all">All</option>
            <option value="attended">Attended</option>
            <option value="not_attended">Not Yet Attended</option>
        </select>
    </div>
</div>

<div class="attendance-table-wrapper">
    <table class="attendance-table">
        <thead>
            <tr>
                <th>Participant Name</th>
                <th>Email</th>
                <th>User Type</th>
                <th>Role</th>
                <th>Registration Status</th>
                <th>Attendance Status</th>
                <th>Check-in Time</th>
                <th style="text-align: right;">Action</th>
            </tr>
        </thead>
        <tbody id="eventAttendanceTableBody">
            @forelse ($participants as $participant)
                @php
                    $regKey = strtolower($participant['registration_status']);
                    $regBadge = $registrationBadgeClasses[$regKey] ?? $registrationBadgeClasses['pending'];
                    $initial = \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($participant['name'], 0, 1));
                @endphp
                <tr
                    data-attendance-row
                    data-attendance-status="{{ $participant['attendance_status'] }}"
                    data-search-text="{{ $participant['search_text'] }}"
                >
                    <td>
                        <div class="flex items-center gap-3">
                            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-[#3B82F6] text-xs font-black text-white">
                                {{ $initial }}
                            </div>
                            <span class="font-black text-[#F8FAFC]">{{ $participant['name'] }}</span>
                        </div>
                    </td>
                    <td class="text-[#94A3B8]">{{ $participant['email'] }}</td>
                    <td>{{ $participant['user_type'] }}</td>
                    <td>{{ $participant['role'] }}</td>
                    <td>
                        <span class="inline-flex rounded-full border px-2.5 py-1 text-xs font-black {{ $regBadge }}">
                            {{ $participant['registration_status_label'] }}
                        </span>
                    </td>
                    <td>
                        @if ($participant['attendance_status'] === 'attended')
                            <span class="inline-flex rounded-full border border-[#22C55E]/40 bg-[#22C55E]/15 px-2.5 py-1 text-xs font-black text-[#86EFAC]">
                                Attended
                            </span>
                        @else
                            <span class="inline-flex rounded-full border border-[#FACC15]/40 bg-[#FACC15]/15 px-2.5 py-1 text-xs font-black text-[#FACC15]">
                                Not Yet Attended
                            </span>
                        @endif
                    </td>
                    <td>{{ $participant['check_in_time'] ?? '—' }}</td>
                    <td style="text-align: right;">
                        @if ($participant['registration_id'])
                            <a
                                href="{{ $participantsUrl }}?registration_id={{ $participant['registration_id'] }}"
                                class="inline-flex rounded-xl border border-[#60A5FA]/25 bg-[#13284A]/70 px-3 py-1.5 text-xs font-black uppercase tracking-wide text-[#F8FAFC] transition hover:border-[#60A5FA]/50 hover:bg-[#13284A]"
                            >
                                View
                            </a>
                        @else
                            <span class="text-xs text-[#64748B]">—</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr id="eventAttendanceEmptyRow">
                    <td colspan="8" style="text-align: center; padding: 2.5rem 1rem; color: #94a3b8;">
                        No participants registered for this event yet.
                    </td>
                </tr>
            @endforelse
            <tr id="eventAttendanceFilterEmptyRow" class="hidden">
                <td colspan="8" style="text-align: center; padding: 2.5rem 1rem; color: #94a3b8;">
                    No participants match your search or filter.
                </td>
            </tr>
        </tbody>
    </table>
</div>

<script>
    (function () {
        const searchInput = document.getElementById('eventAttendanceSearchInput');
        const statusFilter = document.getElementById('eventAttendanceStatusFilter');
        const rows = Array.from(document.querySelectorAll('[data-attendance-row]'));
        const filterEmptyRow = document.getElementById('eventAttendanceFilterEmptyRow');

        const applyAttendanceFilters = () => {
            const searchValue = String(searchInput?.value || '').trim().toLowerCase();
            const statusValue = String(statusFilter?.value || 'all');
            let visibleCount = 0;

            rows.forEach((row) => {
                const searchText = String(row.dataset.searchText || '');
                const attendanceStatus = String(row.dataset.attendanceStatus || '');
                const matchesSearch = searchValue === '' || searchText.includes(searchValue);
                const matchesStatus = statusValue === 'all' || attendanceStatus === statusValue;
                const shouldShow = matchesSearch && matchesStatus;

                row.style.display = shouldShow ? '' : 'none';
                if (shouldShow) {
                    visibleCount += 1;
                }
            });

            if (filterEmptyRow) {
                const showFilterEmpty = rows.length > 0 && visibleCount === 0;
                filterEmptyRow.classList.toggle('hidden', !showFilterEmpty);
            }
        };

        searchInput?.addEventListener('input', applyAttendanceFilters);
        statusFilter?.addEventListener('change', applyAttendanceFilters);
    })();
</script>
