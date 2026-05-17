@extends('layouts.app')

@section('content')
@php
    $statusBadgeClasses = [
        'green' => 'border-[#22C55E]/40 bg-[#22C55E]/15 text-[#86EFAC]',
        'gold'  => 'border-[#FACC15]/40 bg-[#FACC15]/15 text-[#FACC15]',
        'red'   => 'border-[#EF4444]/40 bg-[#EF4444]/15 text-[#FCA5A5]',
    ];
    $formatEventTypeLabel = static function (?string $eventType): string {
        return str_contains(strtolower(trim((string) $eventType)), 'conference')
            ? 'Conference Event'
            : 'School Event';
    };
@endphp
<div class="min-h-screen bg-gradient-to-br from-[#0F1E36] via-[#132B4A] to-[#0F1E36] font-sans text-[#F8FAFC]">
    <div class="flex">

        @include('admin.partials.sidebar')

        <main class="ml-[270px] min-h-screen w-full">

            @include('admin.partials.topbar', ['topbarTitle' => 'Participants'])

            <section class="px-9 py-10">

                {{-- Page Header --}}
                <div>
                    <div class="flex items-center gap-3">
                        <div class="h-7 w-1 rounded-full bg-[#60A5FA]"></div>
                        <h1 class="text-[28px] font-black tracking-tight text-[#F8FAFC]">
                            PARTICIPANTS LIST
                        </h1>
                    </div>
                    <p class="mt-2 text-sm text-[#CBD5E1]">
                        View registered attendees, approve submissions, and monitor check-ins.
                    </p>
                </div>

                @if (session('status_message'))
                    <div
                        class="mt-8 rounded-2xl border px-5 py-4 text-sm font-bold backdrop-blur-md {{ session('status_type') === 'warning' ? 'border-[#FACC15]/40 bg-[#FACC15]/12 text-[#FACC15]' : 'border-[#22C55E]/40 bg-[#22C55E]/12 text-[#86EFAC]' }}"
                        role="status"
                    >
                        {{ session('status_message') }}
                    </div>
                @endif

                {{-- Main Table Container --}}
                <section class="mt-16 overflow-hidden rounded-2xl border border-[#60A5FA]/20 bg-[#1E375A]/65 shadow-lg shadow-black/20 backdrop-blur-md" aria-label="Participants table">
                    <div class="flex flex-col gap-4 border-b border-[#1E3357] bg-[#10213A]/70 px-5 py-5 md:flex-row md:items-center md:justify-between">
                        <div class="flex items-center gap-3">
                            <svg class="h-6 w-6 shrink-0 text-[#60A5FA]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m8-4a4 4 0 10-8 0m8 0a4 4 0 01-8 0"/>
                            </svg>
                            <h2 class="text-2xl font-black uppercase tracking-wide text-[#F8FAFC]">
                                All Registrations
                            </h2>
                            <span id="participantsCount" class="flex h-6 min-w-6 items-center justify-center rounded-full bg-[#3B82F6] px-2 text-xs font-black text-white">
                                {{ $participants->count() }}
                            </span>
                        </div>

                        <div class="flex w-full min-w-0 flex-row flex-nowrap items-center gap-3 lg:w-auto lg:justify-end">
                            <div class="relative min-w-0 flex-1 lg:w-[240px] lg:flex-none">
                                <svg class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-[#64748B]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m1.35-5.65a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                <input
                                    id="participantSearchInput"
                                    type="search"
                                    placeholder="Search participants..."
                                    class="h-11 w-full rounded-2xl border border-[#60A5FA]/20 bg-[#0D1B31]/70 pl-12 pr-4 text-sm font-medium text-[#F8FAFC] outline-none transition placeholder:text-[#64748B] focus:border-[#60A5FA] focus:ring-2 focus:ring-[#60A5FA]/30"
                                    aria-label="Search participants"
                                >
                            </div>
                            <select id="participantStatusFilter" class="h-11 w-[150px] shrink-0 rounded-2xl border border-[#60A5FA]/20 bg-[#0D1B31]/70 px-4 text-sm font-black text-[#F8FAFC] outline-none transition focus:border-[#60A5FA] focus:ring-2 focus:ring-[#60A5FA]/30" aria-label="Filter by approval status">
                                <option value="all" class="bg-[#0D1B31] text-[#F8FAFC]">All Status</option>
                                <option value="pending" class="bg-[#0D1B31] text-[#F8FAFC]">Pending</option>
                                <option value="approved" class="bg-[#0D1B31] text-[#F8FAFC]">Approved</option>
                            </select>
                            <select id="participantEventTypeFilter" class="h-11 w-[175px] shrink-0 rounded-2xl border border-[#60A5FA]/20 bg-[#0D1B31]/70 px-4 text-sm font-black text-[#F8FAFC] outline-none transition focus:border-[#60A5FA] focus:ring-2 focus:ring-[#60A5FA]/30" aria-label="Filter by event type">
                                <option value="all" class="bg-[#0D1B31] text-[#F8FAFC]">All Event Types</option>
                                <option value="school" class="bg-[#0D1B31] text-[#F8FAFC]">School Event</option>
                                <option value="conference" class="bg-[#0D1B31] text-[#F8FAFC]">Conference Event</option>
                            </select>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full min-w-[1050px] border-collapse">
                            <thead>
                                <tr class="border-b border-[#1E3357] bg-[#0D1B31]/70">
                                    <th class="px-5 py-5 text-left text-xs font-black uppercase tracking-widest text-[#94A3B8]">
                                        Participant
                                    </th>
                                    <th class="px-5 py-5 text-left text-xs font-black uppercase tracking-widest text-[#94A3B8]">
                                        Registered Event
                                    </th>
                                    <th class="px-5 py-5 text-left text-xs font-black uppercase tracking-widest text-[#94A3B8]">
                                        Event Type
                                    </th>
                                    <th class="px-5 py-5 text-left text-xs font-black uppercase tracking-widest text-[#94A3B8]">
                                        Status
                                    </th>
                                    <th class="px-5 py-5 text-right text-xs font-black uppercase tracking-widest text-[#94A3B8]">
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($participants as $participant)
                                    @php
                                        $name = trim($participant['name'] ?? '');
                                        $initial = $name !== '' ? \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($name, 0, 1)) : '?';
                                        $badgeClass = $statusBadgeClasses[$participant['status_class'] ?? ''] ?? $statusBadgeClasses['gold'];
                                        $eventTypeLabel = $formatEventTypeLabel($participant['event_type'] ?? '');
                                        $isConferenceEvent = $eventTypeLabel === 'Conference Event';
                                        $eventTypeKey = $isConferenceEvent ? 'conference' : 'school';
                                        $searchText = strtolower(trim(implode(' ', [
                                            $participant['name'] ?? '',
                                            $participant['email'] ?? '',
                                            $participant['event_name'] ?? '',
                                        ])));
                                    @endphp
                                    <tr
                                        class="border-b border-[#1E3357]/60 transition last:border-b-0 hover:bg-[#13284A]/60"
                                        data-participant-row
                                        data-registration-status="{{ $participant['registration_status'] ?? '' }}"
                                        data-event-type="{{ $eventTypeKey }}"
                                        data-search-text="{{ $searchText }}"
                                    >
                                        <td class="px-5 py-5">
                                            <div class="flex items-center gap-4">
                                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#3B82F6] text-sm font-black text-white">
                                                    {{ $initial }}
                                                </div>
                                                <div>
                                                    <h3 class="text-sm font-black text-[#F8FAFC]">
                                                        {{ $name !== '' ? $name : '—' }}
                                                    </h3>
                                                    <p class="mt-1 text-xs font-medium text-[#94A3B8]">
                                                        {{ $participant['email'] }}
                                                    </p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-5 py-5">
                                            <p class="text-sm font-black text-[#F8FAFC]">
                                                {{ $participant['event_name'] }}
                                            </p>
                                        </td>
                                        <td class="px-5 py-5">
                                            <span class="inline-flex rounded-full border px-3 py-1.5 text-xs font-black {{ $isConferenceEvent ? 'border-[#60A5FA]/40 bg-[#60A5FA]/15 text-[#93C5FD]' : 'border-[#94A3B8]/30 bg-[#94A3B8]/10 text-[#CBD5E1]' }}">
                                                {{ $eventTypeLabel }}
                                            </span>
                                        </td>
                                        <td class="px-5 py-5">
                                            <span class="inline-flex rounded-full border px-4 py-2 text-xs font-black {{ $badgeClass }}">
                                                {{ $participant['status_label'] }}
                                            </span>
                                        </td>
                                        <td class="px-5 py-5 text-right">
                                            <button
                                                type="button"
                                                class="details-btn rounded-2xl border border-[#60A5FA]/25 bg-[#13284A]/70 px-5 py-2 text-sm font-black uppercase tracking-wide text-[#F8FAFC] transition hover:border-[#60A5FA]/50 hover:bg-[#13284A]"
                                                data-registration-id="{{ $participant['registration_id'] }}"
                                            >
                                                View Details
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                @endforelse
                                <tr id="participantsEmptyState" class="{{ $participants->isEmpty() ? '' : 'hidden' }}">
                                    <td id="participantsEmptyMessage" colspan="5" class="px-5 py-10 text-center text-sm font-medium text-[#94A3B8]">
                                        No participant registrations found yet.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </section>
            </section>
        </main>
    </div>
</div>

{{-- PARTICIPANT DETAILS MODAL --}}
<div
    id="participantDetailsModal"
    class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/70 px-4 py-6 backdrop-blur-sm"
    role="dialog"
    aria-modal="true"
    aria-labelledby="participantDetailsModalTitle"
    aria-hidden="true"
>
    <div class="relative max-h-[min(90vh,880px)] w-full max-w-[650px] overflow-y-auto rounded-3xl border border-[#60A5FA]/25 bg-[#10213A] shadow-2xl shadow-black/40" onclick="event.stopPropagation()">
        <div class="flex items-start justify-between border-b border-[#1E3357] bg-[#0D1B31] px-6 py-6 text-white">
            <div class="min-w-0 pr-4">
                <h2 id="participantDetailsModalTitle" class="text-2xl font-black uppercase tracking-wide text-[#F8FAFC]">
                    Participant Details
                </h2>
                <p id="participantModalEventSubtitle" class="mt-2 text-sm text-[#60A5FA]">—</p>
            </div>

            <button
                type="button"
                id="participantDetailsClose"
                onclick="closeParticipantDetailsModal()"
                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full border border-[#60A5FA]/20 bg-[#13284A]/70 text-[#F8FAFC] transition hover:border-[#60A5FA]/50 hover:bg-[#13284A]"
                aria-label="Close"
            >
                <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2.3" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <div class="px-7 py-7">
            <section class="rounded-2xl border border-[#60A5FA]/20 bg-[#13284A]/60 p-6">
                <div class="flex items-center gap-5">
                    <div
                        id="participantModalInitial"
                        class="flex h-16 w-16 shrink-0 items-center justify-center rounded-2xl bg-[#3B82F6] text-2xl font-black text-white"
                    >
                        —
                    </div>

                    <div class="min-w-0">
                        <h3 id="participantModalName" class="text-xl font-black text-[#F8FAFC]">—</h3>
                        <p id="participantModalEmail" class="mt-1 text-sm text-[#94A3B8]">—</p>
                        <span
                            id="participantModalStatus"
                            class="mt-2 inline-flex rounded-xl border border-[#FACC15]/40 bg-[#FACC15]/15 px-3 py-1 text-xs font-black text-[#FACC15]"
                        >
                            —
                        </span>
                    </div>
                </div>

                <p id="participantModalLevelRegion" class="mt-4 hidden text-sm font-bold text-[#CBD5E1]"></p>

                <div class="my-6 border-t border-[#1E3357]"></div>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div class="rounded-2xl border border-[#60A5FA]/20 bg-[#0D1B31]/60 p-4">
                        <p class="text-sm font-black uppercase tracking-widest text-[#60A5FA]">
                            School / University
                        </p>
                        <p id="participantModalSchool" class="mt-2 text-sm font-black text-[#F8FAFC]">—</p>
                    </div>

                    <div class="rounded-2xl border border-[#60A5FA]/20 bg-[#0D1B31]/60 p-4">
                        <p class="text-sm font-black uppercase tracking-widest text-[#60A5FA]">
                            User Type
                        </p>
                        <p id="participantModalUserType" class="mt-2 text-sm font-black text-[#F8FAFC]">—</p>
                    </div>

                    <div class="rounded-2xl border border-[#60A5FA]/20 bg-[#0D1B31]/60 p-4">
                        <p class="text-sm font-black uppercase tracking-widest text-[#60A5FA]">
                            Role
                        </p>
                        <p id="participantModalRole" class="mt-2 text-sm font-black text-[#F8FAFC]">—</p>
                    </div>

                    <div class="rounded-2xl border border-[#60A5FA]/20 bg-[#0D1B31]/60 p-4">
                        <p class="text-sm font-black uppercase tracking-widest text-[#60A5FA]">
                            Registered Event
                        </p>
                        <p id="participantModalEvent" class="mt-2 text-sm font-black text-[#F8FAFC]">—</p>
                    </div>
                </div>
            </section>

            <section id="participantModalPaperSection" class="mt-5 hidden rounded-2xl border border-[#60A5FA]/20 bg-[#13284A]/60 p-5" aria-label="Research paper submission">
                <div class="flex items-center gap-3 border-b border-[#1E3357] pb-4">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl border border-[#60A5FA]/25 bg-[#0D1B31]/70 text-[#60A5FA]">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-black uppercase tracking-wide text-[#F8FAFC]">Research Paper</h3>
                        <p class="text-xs font-medium text-[#94A3B8]">Conference submission</p>
                    </div>
                </div>

                <div id="paperPresent" class="mt-4 hidden">
                    <div class="rounded-2xl border border-[#60A5FA]/20 bg-[#0D1B31]/60 p-4">
                        <p id="detailPaperName" class="text-sm font-black text-[#F8FAFC]">—</p>
                        <div class="mt-2 flex flex-wrap gap-2">
                            <span id="detailPaperType" class="rounded-lg border border-[#60A5FA]/25 bg-[#13284A]/70 px-2 py-1 text-xs font-bold text-[#CBD5E1]">—</span>
                            <span id="detailPaperSize" class="rounded-lg border border-[#60A5FA]/25 bg-[#13284A]/70 px-2 py-1 text-xs font-bold text-[#CBD5E1]">—</span>
                            <span id="detailPaperStatus" class="rounded-lg border border-[#60A5FA]/25 bg-[#13284A]/70 px-2 py-1 text-xs font-bold text-[#CBD5E1]">—</span>
                        </div>
                        <div class="mt-4 grid grid-cols-1 gap-3 sm:grid-cols-2">
                            <div class="rounded-xl border border-[#1E3357] bg-[#13284A]/70 px-3 py-2">
                                <p class="text-[10px] font-black uppercase tracking-wider text-[#94A3B8]">Submission status</p>
                                <p id="detailPaperStatusLabel" class="mt-1 text-xs font-black text-[#F8FAFC]">—</p>
                            </div>
                            <div class="rounded-xl border border-[#1E3357] bg-[#13284A]/70 px-3 py-2">
                                <p class="text-[10px] font-black uppercase tracking-wider text-[#94A3B8]">Submitted at</p>
                                <p id="detailPaperSubmittedAt" class="mt-1 text-xs font-black text-[#F8FAFC]">—</p>
                            </div>
                        </div>
                        <a
                            id="detailPaperDownload"
                            href="#"
                            class="mt-4 inline-flex w-full items-center justify-center rounded-2xl bg-[#3B82F6] px-4 py-3 text-center text-sm font-black uppercase tracking-wide text-white shadow-sm transition hover:bg-[#2563EB] sm:w-auto"
                        >
                            Download Paper
                        </a>
                    </div>
                </div>

                <div id="paperEmpty" class="mt-4 hidden rounded-2xl border border-dashed border-[#60A5FA]/25 bg-[#0D1B31]/40 px-4 py-4 text-center text-sm font-semibold text-[#94A3B8]">
                    No research paper has been uploaded for this participant yet.
                </div>
            </section>

            <div id="participantModalPendingActions" class="mt-5 hidden grid grid-cols-1 gap-4 md:grid-cols-2">
                <form id="rejectApplicationForm" method="POST" action="#">
                    @csrf
                    <button
                        type="submit"
                        id="rejectApplicationBtn"
                        class="h-14 w-full rounded-2xl border border-[#EF4444]/50 bg-[#EF4444]/10 text-sm font-black uppercase tracking-wide text-[#FCA5A5] transition hover:border-[#EF4444] hover:bg-[#EF4444]/20"
                    >
                        Reject
                    </button>
                </form>

                <form id="approveApplicationForm" method="POST" action="#">
                    @csrf
                    <button
                        type="submit"
                        id="approveApplicationBtn"
                        class="flex h-14 w-full items-center justify-center gap-3 rounded-2xl bg-[#3B82F6] text-sm font-black uppercase tracking-wide text-white shadow-sm transition hover:bg-[#2563EB]"
                    >
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2.3" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75l2 2 4-5"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9 9 0 100-18 9 9 0 000 18z"/>
                        </svg>
                        Approve
                    </button>
                </form>
            </div>

            <div
                id="participantModalApprovedNotice"
                class="mt-5 hidden rounded-2xl border border-[#22C55E]/40 bg-[#22C55E]/15 px-5 py-4 text-center"
            >
                <div class="flex items-center justify-center gap-3 text-sm font-black uppercase tracking-wide text-[#86EFAC]">
                    <svg class="h-6 w-6 shrink-0" fill="none" stroke="currentColor" stroke-width="2.3" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75l2 2 4-5"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9 9 0 100-18 9 9 0 000 18z"/>
                    </svg>
                    Registration Approved
                </div>
            </div>

            <div
                id="participantModalRejectedNotice"
                class="mt-5 hidden rounded-2xl border border-[#EF4444]/40 bg-[#EF4444]/15 px-5 py-4 text-center text-sm font-black uppercase tracking-wide text-[#FCA5A5]"
            >
                Registration Rejected
            </div>
        </div>
    </div>
</div>

<script>
    (function() {
        const participantRows = @json($participants->values());
        const participantMap = new Map(participantRows.map(row => [String(row.registration_id), row]));
        const participantTableRows = Array.from(document.querySelectorAll('[data-participant-row]'));
        const participantSearchInput = document.getElementById('participantSearchInput');
        const participantStatusFilter = document.getElementById('participantStatusFilter');
        const participantEventTypeFilter = document.getElementById('participantEventTypeFilter');
        const participantsCount = document.getElementById('participantsCount');
        const participantsEmptyState = document.getElementById('participantsEmptyState');
        const participantsEmptyMessage = document.getElementById('participantsEmptyMessage');

        const modal = document.getElementById('participantDetailsModal');
        const closeBtn = document.getElementById('participantDetailsClose');
        const detailsButtons = Array.from(document.querySelectorAll('.details-btn'));

        const participantModalEventSubtitle = document.getElementById('participantModalEventSubtitle');
        const participantModalInitial = document.getElementById('participantModalInitial');
        const participantModalName = document.getElementById('participantModalName');
        const participantModalEmail = document.getElementById('participantModalEmail');
        const participantModalStatus = document.getElementById('participantModalStatus');
        const participantModalLevelRegion = document.getElementById('participantModalLevelRegion');
        const participantModalSchool = document.getElementById('participantModalSchool');
        const participantModalUserType = document.getElementById('participantModalUserType');
        const participantModalRole = document.getElementById('participantModalRole');
        const participantModalEvent = document.getElementById('participantModalEvent');

        const paperSection = document.getElementById('participantModalPaperSection');
        const paperPresent = document.getElementById('paperPresent');
        const paperEmpty = document.getElementById('paperEmpty');
        const detailPaperName = document.getElementById('detailPaperName');
        const detailPaperType = document.getElementById('detailPaperType');
        const detailPaperSize = document.getElementById('detailPaperSize');
        const detailPaperStatus = document.getElementById('detailPaperStatus');
        const detailPaperStatusLabel = document.getElementById('detailPaperStatusLabel');
        const detailPaperSubmittedAt = document.getElementById('detailPaperSubmittedAt');
        const detailPaperDownload = document.getElementById('detailPaperDownload');

        const pendingActions = document.getElementById('participantModalPendingActions');
        const approvedNotice = document.getElementById('participantModalApprovedNotice');
        const rejectedNotice = document.getElementById('participantModalRejectedNotice');
        const rejectApplicationForm = document.getElementById('rejectApplicationForm');
        const approveApplicationForm = document.getElementById('approveApplicationForm');
        const rejectApplicationBtn = document.getElementById('rejectApplicationBtn');
        const approveApplicationBtn = document.getElementById('approveApplicationBtn');

        const statusBadgeBase = 'mt-2 inline-flex rounded-xl border px-3 py-1 text-xs font-black ';
        const statusBadgeByClass = {
            green: 'border-[#22C55E]/40 bg-[#22C55E]/15 text-[#86EFAC]',
            gold:  'border-[#FACC15]/40 bg-[#FACC15]/15 text-[#FACC15]',
            red:   'border-[#EF4444]/40 bg-[#EF4444]/15 text-[#FCA5A5]',
        };

        function applyParticipantFilter() {
            const searchQuery = (participantSearchInput ? participantSearchInput.value : '').trim().toLowerCase();
            const selectedStatus = participantStatusFilter ? participantStatusFilter.value : 'all';
            const selectedEventType = participantEventTypeFilter ? participantEventTypeFilter.value : 'all';
            let visibleCount = 0;

            participantTableRows.forEach(row => {
                const registrationStatus = (row.dataset.registrationStatus || '').toLowerCase();
                const eventType = (row.dataset.eventType || '').toLowerCase();
                const searchText = (row.dataset.searchText || '').toLowerCase();

                const matchesSearch = searchQuery === '' || searchText.includes(searchQuery);
                const matchesStatus = selectedStatus === 'all' || registrationStatus === selectedStatus;
                const matchesEventType = selectedEventType === 'all' || eventType === selectedEventType;
                const shouldShow = matchesSearch && matchesStatus && matchesEventType;

                row.classList.toggle('hidden', !shouldShow);

                if (shouldShow) {
                    visibleCount += 1;
                }
            });

            if (participantsCount) {
                participantsCount.textContent = String(visibleCount);
            }

            if (participantsEmptyState && participantsEmptyMessage) {
                const showEmpty = visibleCount === 0;
                participantsEmptyState.classList.toggle('hidden', !showEmpty);

                if (searchQuery !== '') {
                    participantsEmptyMessage.textContent = 'No participants match your search.';
                } else if (selectedStatus !== 'all' || selectedEventType !== 'all') {
                    participantsEmptyMessage.textContent = 'No participants match the selected filters.';
                } else {
                    participantsEmptyMessage.textContent = 'No participant registrations found yet.';
                }
            }
        }

        function normalizeText(value, fallback = 'Not provided') {
            const text = String(value ?? '').trim();
            return text !== '' ? text : fallback;
        }

        function formatPaperStatus(value) {
            const status = normalizeText(value, 'Unknown');
            return status
                .replaceAll('_', ' ')
                .split(' ')
                .map(token => token ? (token.charAt(0).toUpperCase() + token.slice(1).toLowerCase()) : '')
                .join(' ');
        }

        function participantInitial(name) {
            const n = String(name ?? '').trim();
            if (!n) {
                return '?';
            }
            return n.charAt(0).toUpperCase();
        }

        function openModal() {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            modal.setAttribute('aria-hidden', 'false');
        }

        function closeModal() {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            modal.setAttribute('aria-hidden', 'true');
        }

        window.closeParticipantDetailsModal = closeModal;

        if (participantSearchInput) {
            participantSearchInput.addEventListener('input', applyParticipantFilter);
        }

        if (participantStatusFilter) {
            participantStatusFilter.addEventListener('change', applyParticipantFilter);
        }

        if (participantEventTypeFilter) {
            participantEventTypeFilter.addEventListener('change', applyParticipantFilter);
        }

        applyParticipantFilter();

        function openDetails(registrationId) {
            const participant = participantMap.get(String(registrationId));
            if (!participant) {
                return;
            }

            const eventName = normalizeText(participant.event_name, 'Unknown Event');
            participantModalEventSubtitle.textContent = eventName;
            participantModalInitial.textContent = participantInitial(participant.name);
            participantModalName.textContent = normalizeText(participant.name, 'Unknown Participant');
            participantModalEmail.textContent = normalizeText(participant.email, 'Not provided');

            const label = normalizeText(participant.status_label, '—');
            participantModalStatus.textContent = label;
            const badgeKey = participant.status_class in statusBadgeByClass ? participant.status_class : 'gold';
            participantModalStatus.className = statusBadgeBase + statusBadgeByClass[badgeKey];

            const lr = normalizeText(participant.level_region, '');
            if (lr !== 'Not provided' && lr !== '') {
                participantModalLevelRegion.textContent = 'Level / Region: ' + lr;
                participantModalLevelRegion.classList.remove('hidden');
            } else {
                participantModalLevelRegion.classList.add('hidden');
            }

            participantModalSchool.textContent = normalizeText(participant.institution ?? participant.school_affiliation, 'Not provided');
            participantModalUserType.textContent = normalizeText(participant.user_type, 'Not provided');
            participantModalRole.textContent = normalizeText(participant.participant_role, 'Not provided');
            participantModalEvent.textContent = eventName;

            const isConference = String(participant.event_type || '').trim() === 'Conference';
            const participantRole = String(participant.participant_role || '').trim().toLowerCase();
            const isPresenter = participantRole === 'presentor';
            const paper = participant.paper || {};

            if (isConference && isPresenter) {
                paperSection.classList.remove('hidden');
                if (paper.has_file) {
                    paperPresent.classList.remove('hidden');
                    paperEmpty.classList.add('hidden');

                    detailPaperName.textContent = normalizeText(paper.file_name, 'Unknown file');
                    detailPaperType.textContent = normalizeText(paper.file_type, 'Unknown');
                    detailPaperSize.textContent = normalizeText(paper.file_size, 'Not available');
                    const formattedPaperStatus = formatPaperStatus(paper.status);
                    detailPaperStatus.textContent = formattedPaperStatus;
                    detailPaperStatusLabel.textContent = formattedPaperStatus;
                    detailPaperSubmittedAt.textContent = normalizeText(paper.submitted_at, 'Not available');

                    const downloadUrl = normalizeText(paper.download_url, '');
                    detailPaperDownload.href = downloadUrl;
                    detailPaperDownload.classList.toggle('pointer-events-none', downloadUrl === '');
                    detailPaperDownload.classList.toggle('opacity-60', downloadUrl === '');
                } else {
                    paperPresent.classList.add('hidden');
                    paperEmpty.classList.remove('hidden');
                    detailPaperDownload.removeAttribute('href');
                }
            } else {
                paperSection.classList.add('hidden');
                paperPresent.classList.add('hidden');
                paperEmpty.classList.add('hidden');
            }

            const registrationStatus = String(participant.registration_status || '').trim().toLowerCase();
            const canReview = registrationStatus === 'pending' && (!isConference || !isPresenter || Boolean(paper.has_file));
            const isApprovedFlow = registrationStatus === 'approved' || String(participant.status_label || '').toLowerCase().includes('checked');
            const isRejected = registrationStatus === 'rejected';

            pendingActions.classList.toggle('hidden', !canReview);
            approvedNotice.classList.toggle('hidden', !(isApprovedFlow && !canReview));
            rejectedNotice.classList.toggle('hidden', !isRejected);

            if (canReview) {
                const rejectUrl = normalizeText(participant.reject_url, '');
                const approveUrl = normalizeText(participant.approve_url, '');
                rejectApplicationForm.action = rejectUrl;
                approveApplicationForm.action = approveUrl;
                rejectApplicationBtn.disabled = rejectUrl === '';
                approveApplicationBtn.disabled = approveUrl === '';
            } else {
                rejectApplicationForm.action = '#';
                approveApplicationForm.action = '#';
                rejectApplicationBtn.disabled = true;
                approveApplicationBtn.disabled = true;
            }

            openModal();
        }

        detailsButtons.forEach(button => {
            button.addEventListener('click', () => openDetails(button.dataset.registrationId));
        });

        rejectApplicationForm.addEventListener('submit', (event) => {
            const shouldProceed = window.confirm('Reject this application? This will mark the registration as rejected.');
            if (!shouldProceed) {
                event.preventDefault();
            }
        });

        approveApplicationForm.addEventListener('submit', (event) => {
            const shouldProceed = window.confirm('Approve this application and send the digital ID email now?');
            if (!shouldProceed) {
                event.preventDefault();
            }
        });

        closeBtn.addEventListener('click', closeModal);
        modal.addEventListener('click', (event) => {
            if (event.target === modal) {
                closeModal();
            }
        });

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && !modal.classList.contains('hidden')) {
                closeModal();
            }
        });
    })();
</script>
@endsection
