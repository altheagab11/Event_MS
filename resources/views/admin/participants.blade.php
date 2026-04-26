@extends('layouts.app')

@section('content')
@php
    $eventFilterList = $participants->pluck('event_name')->unique()->filter()->sort()->values();
    $statusBadgeClasses = [
        'green' => 'border-[#86EFAC] bg-[#ECFDF5] text-[#047857]',
        'gold' => 'border-[#FCD34D] bg-[#FFFBEB] text-[#D97706]',
        'red' => 'border-[#FECACA] bg-[#FEF2F2] text-[#B91C1C]',
    ];
@endphp
<div class="min-h-screen bg-[#F6F8FB] font-sans text-[#111827]">
    <div class="flex">

        {{-- SIDEBAR (match dashboard) --}}
        <aside class="fixed left-0 top-0 z-40 h-screen w-[270px] border-r border-[#E5EAF1] bg-[#FBFAF7]">
            <div class="flex h-[78px] items-center border-b border-[#E5EAF1] px-6">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-[#111827] text-white">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3M4 11h16M5 5h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-[13px] font-black tracking-[0.18em] text-[#0F172A]">EVENT</h1>
                        <p class="text-[10px] font-bold tracking-[0.16em] text-[#C8A25A]">MANAGEMENT SYSTEM</p>
                    </div>
                </div>
            </div>

            <div class="px-6 pt-10">
                <div class="flex items-center gap-3 rounded-2xl border border-[#DDE6F2] bg-[#F4F8FC] p-3">
                    <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-[#111827] text-sm font-black text-white">
                        A
                    </div>
                    <div>
                        <h2 class="text-sm font-extrabold text-[#111827]">Administrator</h2>
                        <p class="text-xs text-[#7B8AA0]">admin@system.edu.ph</p>
                    </div>
                </div>
            </div>

            <nav class="mt-6 px-4">
                <p class="px-2 text-[11px] font-black uppercase tracking-widest text-[#D6DEE9]">
                    Main Navigation
                </p>

                <div class="mt-4 space-y-2">
                    <a href="{{ route('admin.dashboard') }}"
                       class="flex items-center {{ request()->routeIs('admin.dashboard') ? 'justify-between rounded-2xl border-l-2 border-[#D2A64B] bg-[#FFF8EA] px-4 py-3 text-sm font-black text-[#0F172A]' : 'gap-3 rounded-2xl px-4 py-3 text-sm font-bold text-[#53657F] hover:bg-[#F4F7FB]' }}">
                        <span class="flex items-center gap-3">
                            <svg class="h-5 w-5 {{ request()->routeIs('admin.dashboard') ? 'text-[#D2A64B]' : '' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 4h6v6H4V4zm10 0h6v6h-6V4zM4 14h6v6H4v-6zm10 0h6v6h-6v-6z"/>
                            </svg>
                            Dashboard
                        </span>
                        @if (request()->routeIs('admin.dashboard'))
                            <span class="text-[#D2A64B]">›</span>
                        @endif
                    </a>

                    <a href="{{ route('admin.events') }}"
                       class="flex items-center {{ request()->routeIs('admin.events*') ? 'justify-between rounded-2xl border-l-2 border-[#D2A64B] bg-[#FFF8EA] px-4 py-3 text-sm font-black text-[#0F172A]' : 'gap-3 rounded-2xl px-4 py-3 text-sm font-bold text-[#53657F] hover:bg-[#F4F7FB]' }}">
                        <span class="flex items-center gap-3">
                            <svg class="h-5 w-5 {{ request()->routeIs('admin.events*') ? 'text-[#D2A64B]' : '' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3M4 11h16M5 5h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z"/>
                            </svg>
                            Events
                        </span>
                        @if (request()->routeIs('admin.events*'))
                            <span class="text-[#D2A64B]">›</span>
                        @endif
                    </a>

                    <a href="{{ route('admin.participants') }}"
                       class="flex items-center {{ request()->routeIs('admin.participants*') ? 'justify-between rounded-2xl border-l-2 border-[#D2A64B] bg-[#FFF8EA] px-4 py-3 text-sm font-black text-[#0F172A]' : 'gap-3 rounded-2xl px-4 py-3 text-sm font-bold text-[#53657F] hover:bg-[#F4F7FB]' }}">
                        <span class="flex items-center gap-3">
                            <svg class="h-5 w-5 {{ request()->routeIs('admin.participants*') ? 'text-[#D2A64B]' : '' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m8-4a4 4 0 10-8 0m8 0a4 4 0 01-8 0"/>
                            </svg>
                            Participants
                        </span>
                        @if (request()->routeIs('admin.participants*'))
                            <span class="text-[#D2A64B]">›</span>
                        @endif
                    </a>

                    <a href="{{ route('admin.evaluations') }}"
                       class="flex items-center {{ request()->routeIs('admin.evaluations*') ? 'justify-between rounded-2xl border-l-2 border-[#D2A64B] bg-[#FFF8EA] px-4 py-3 text-sm font-black text-[#0F172A]' : 'gap-3 rounded-2xl px-4 py-3 text-sm font-bold text-[#53657F] hover:bg-[#F4F7FB]' }}">
                        <span class="flex items-center gap-3">
                            <svg class="h-5 w-5 {{ request()->routeIs('admin.evaluations*') ? 'text-[#D2A64B]' : '' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 10h8M8 14h5M5 5h14v12H7l-4 4V7a2 2 0 012-2z"/>
                            </svg>
                            Evaluations
                        </span>
                        @if (request()->routeIs('admin.evaluations*'))
                            <span class="text-[#D2A64B]">›</span>
                        @endif
                    </a>
                </div>
            </nav>

            <div class="absolute bottom-0 left-0 w-full border-t border-[#E5EAF1] px-6 py-6">
                <a href="#" class="mb-5 flex items-center gap-3 text-sm font-bold text-[#7A8BA3]">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317a1.724 1.724 0 013.35 0 1.724 1.724 0 002.573 1.066 1.724 1.724 0 012.37 2.37 1.724 1.724 0 001.065 2.572 1.724 1.724 0 010 3.35 1.724 1.724 0 00-1.066 2.573 1.724 1.724 0 01-2.37 2.37 1.724 1.724 0 00-2.572 1.065 1.724 1.724 0 01-3.35 0 1.724 1.724 0 00-2.573-1.066 1.724 1.724 0 01-2.37-2.37 1.724 1.724 0 00-1.065-2.572 1.724 1.724 0 010-3.35 1.724 1.724 0 001.066-2.573 1.724 1.724 0 012.37-2.37 1.724 1.724 0 002.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Settings
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center gap-3 text-sm font-bold text-[#FF4D4F]">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H9m4 8H5a2 2 0 01-2-2V6a2 2 0 012-2h8"/>
                        </svg>
                        Logout
                    </button>
                </form>
            </div>
        </aside>

        <main class="ml-[270px] min-h-screen w-full">
            <header class="flex h-[78px] items-center justify-between border-b border-[#E5EAF1] bg-white px-9">
                <h2 class="text-sm font-black uppercase tracking-widest text-[#111827]">
                    Participants
                </h2>

                <div class="flex items-center gap-4">
                    <button type="button" class="relative flex h-10 w-10 items-center justify-center rounded-2xl border border-[#DDE6F2] bg-white text-[#53657F]">
                        <span class="absolute right-2 top-2 h-2 w-2 rounded-full bg-[#D2A64B]"></span>
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0a3 3 0 01-6 0"/>
                        </svg>
                    </button>

                    <div class="flex items-center gap-2 rounded-2xl border border-[#DDE6F2] bg-white px-3 py-2">
                        <div class="flex h-7 w-7 items-center justify-center rounded-full bg-[#111827] text-xs font-black text-white">
                            A
                        </div>
                        <span class="text-sm font-bold">Admin</span>
                    </div>
                </div>
            </header>

            <section class="px-9 py-10">
                <div>
                    <div class="flex items-center gap-3">
                        <div class="h-7 w-1 rounded-full bg-[#D2A64B]"></div>
                        <h1 class="text-[28px] font-black tracking-tight text-[#111827]">
                            PARTICIPANTS LIST
                        </h1>
                    </div>
                    <p class="mt-2 text-sm text-[#53657F]">
                        View registered attendees, approve submissions, and monitor check-ins.
                    </p>
                </div>

                @if (session('status_message'))
                    <div
                        class="mt-8 rounded-2xl border px-5 py-4 text-sm font-bold {{ session('status_type') === 'warning' ? 'border-[#F1D49A] bg-[#FFFBF0] text-[#8D5D00]' : 'border-[#B8E3C9] bg-[#EDFFF3] text-[#1D6C3E]' }}"
                        role="status"
                    >
                        {{ session('status_message') }}
                    </div>
                @endif

                <section class="mt-16 overflow-hidden rounded-2xl border border-[#DDE6F2] bg-white" aria-label="Participants table">
                    <div class="flex flex-col gap-4 border-b border-[#E8EEF5] bg-white px-5 py-5 md:flex-row md:items-center md:justify-between">
                        <div class="flex items-center gap-3">
                            <svg class="h-6 w-6 shrink-0 text-[#D2A64B]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m8-4a4 4 0 10-8 0m8 0a4 4 0 01-8 0"/>
                            </svg>
                            <h2 class="text-2xl font-black uppercase tracking-wide text-[#111827]">
                                All Registrations
                            </h2>
                            <span class="flex h-6 min-w-6 items-center justify-center rounded-full bg-[#111827] px-2 text-xs font-black text-white">
                                {{ $participants->count() }}
                            </span>
                        </div>

                        <select class="h-11 w-full rounded-2xl border border-[#DDE6F2] bg-white px-5 text-sm font-black text-[#111827] outline-none focus:border-[#D2A64B] md:w-[260px]" aria-label="Filter by event">
                            <option>All Events</option>
                            @foreach ($eventFilterList as $eventName)
                                <option value="{{ $eventName }}">{{ $eventName }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full min-w-[900px] border-collapse">
                            <thead>
                                <tr class="border-b border-[#E8EEF5] bg-[#F8FAFC]">
                                    <th class="px-5 py-5 text-left text-xs font-black uppercase tracking-widest text-[#64748B]">
                                        Participant
                                    </th>
                                    <th class="px-5 py-5 text-left text-xs font-black uppercase tracking-widest text-[#64748B]">
                                        Registered Event
                                    </th>
                                    <th class="px-5 py-5 text-left text-xs font-black uppercase tracking-widest text-[#64748B]">
                                        Status
                                    </th>
                                    <th class="px-5 py-5 text-right text-xs font-black uppercase tracking-widest text-[#64748B]">
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
                                    @endphp
                                    <tr class="border-b border-[#E8EEF5] transition last:border-b-0 hover:bg-[#F8FAFC]">
                                        <td class="px-5 py-5">
                                            <div class="flex items-center gap-4">
                                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#172233] text-sm font-black text-white">
                                                    {{ $initial }}
                                                </div>
                                                <div>
                                                    <h3 class="text-sm font-black text-[#111827]">
                                                        {{ $name !== '' ? $name : '—' }}
                                                    </h3>
                                                    <p class="mt-1 text-xs font-medium text-[#64748B]">
                                                        {{ $participant['email'] }}
                                                    </p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-5 py-5">
                                            <p class="text-sm font-black text-[#111827]">
                                                {{ $participant['event_name'] }}
                                            </p>
                                        </td>
                                        <td class="px-5 py-5">
                                            <span class="inline-flex rounded-full border px-4 py-2 text-xs font-black {{ $badgeClass }}">
                                                {{ $participant['status_label'] }}
                                            </span>
                                        </td>
                                        <td class="px-5 py-5 text-right">
                                            <button
                                                type="button"
                                                class="details-btn rounded-2xl border border-[#DDE6F2] bg-[#F8FAFC] px-5 py-2 text-sm font-black uppercase tracking-wide text-[#111827] transition hover:bg-[#EEF3F9]"
                                                data-registration-id="{{ $participant['registration_id'] }}"
                                            >
                                                View Details
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-5 py-10 text-center text-sm font-medium text-[#64748B]">
                                            No participant registrations found yet.
                                        </td>
                                    </tr>
                                @endforelse
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
    class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/50 px-4 py-6 backdrop-blur-sm"
    role="dialog"
    aria-modal="true"
    aria-labelledby="participantDetailsModalTitle"
    aria-hidden="true"
>
    <div class="relative max-h-[min(90vh,880px)] w-full max-w-[650px] overflow-y-auto rounded-3xl border border-[#DDE6F2] bg-white shadow-2xl" onclick="event.stopPropagation()">
        <div class="flex items-start justify-between bg-[#172233] px-6 py-6 text-white">
            <div class="min-w-0 pr-4">
                <h2 id="participantDetailsModalTitle" class="text-2xl font-black uppercase tracking-wide">
                    Participant Details
                </h2>
                <p id="participantModalEventSubtitle" class="mt-2 text-sm text-white/70">—</p>
            </div>

            <button
                type="button"
                id="participantDetailsClose"
                onclick="closeParticipantDetailsModal()"
                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-white/10 text-white transition hover:bg-white/20"
                aria-label="Close"
            >
                <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2.3" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <div class="px-7 py-7">
            <section class="rounded-2xl border border-[#DDE6F2] bg-[#F8FAFC] p-6">
                <div class="flex items-center gap-5">
                    <div
                        id="participantModalInitial"
                        class="flex h-16 w-16 shrink-0 items-center justify-center rounded-2xl bg-[#172233] text-2xl font-black text-white"
                    >
                        —
                    </div>

                    <div class="min-w-0">
                        <h3 id="participantModalName" class="text-xl font-black text-[#111827]">—</h3>
                        <p id="participantModalEmail" class="mt-1 text-sm text-[#64748B]">—</p>
                        <span
                            id="participantModalStatus"
                            class="mt-2 inline-flex rounded-xl border border-[#FCD34D] bg-[#FFFBEB] px-3 py-1 text-xs font-black text-[#D97706]"
                        >
                            —
                        </span>
                    </div>
                </div>

                <p id="participantModalLevelRegion" class="mt-4 hidden text-sm font-bold text-[#64748B]"></p>

                <div class="my-6 border-t border-[#DDE6F2]"></div>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div class="rounded-2xl border border-[#DDE6F2] bg-white p-4">
                        <p class="text-sm font-black uppercase tracking-widest text-[#C8A25A]">
                            School / University
                        </p>
                        <p id="participantModalSchool" class="mt-2 text-sm font-black text-[#111827]">—</p>
                    </div>

                    <div class="rounded-2xl border border-[#DDE6F2] bg-white p-4">
                        <p class="text-sm font-black uppercase tracking-widest text-[#C8A25A]">
                            User Type
                        </p>
                        <p id="participantModalUserType" class="mt-2 text-sm font-black text-[#111827]">—</p>
                    </div>

                    <div class="rounded-2xl border border-[#DDE6F2] bg-white p-4">
                        <p class="text-sm font-black uppercase tracking-widest text-[#C8A25A]">
                            Role
                        </p>
                        <p id="participantModalRole" class="mt-2 text-sm font-black text-[#111827]">—</p>
                    </div>

                    <div class="rounded-2xl border border-[#DDE6F2] bg-white p-4">
                        <p class="text-sm font-black uppercase tracking-widest text-[#C8A25A]">
                            Registered Event
                        </p>
                        <p id="participantModalEvent" class="mt-2 text-sm font-black text-[#111827]">—</p>
                    </div>
                </div>
            </section>

            <section id="participantModalPaperSection" class="mt-5 hidden rounded-2xl border border-[#DDE6F2] bg-[#F8FAFC] p-5" aria-label="Research paper submission">
                <div class="flex items-center gap-3 border-b border-[#E8EEF5] pb-4">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-white text-[#172233] shadow-sm ring-1 ring-[#DDE6F2]">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-black uppercase tracking-wide text-[#111827]">Research Paper</h3>
                        <p class="text-xs font-medium text-[#64748B]">Conference submission</p>
                    </div>
                </div>

                <div id="paperPresent" class="mt-4 hidden">
                    <div class="rounded-2xl border border-[#DDE6F2] bg-white p-4">
                        <p id="detailPaperName" class="text-sm font-black text-[#111827]">—</p>
                        <div class="mt-2 flex flex-wrap gap-2">
                            <span id="detailPaperType" class="rounded-lg border border-[#DDE6F2] bg-[#F8FAFC] px-2 py-1 text-xs font-bold text-[#53657F]">—</span>
                            <span id="detailPaperSize" class="rounded-lg border border-[#DDE6F2] bg-[#F8FAFC] px-2 py-1 text-xs font-bold text-[#53657F]">—</span>
                            <span id="detailPaperStatus" class="rounded-lg border border-[#DDE6F2] bg-[#F8FAFC] px-2 py-1 text-xs font-bold text-[#53657F]">—</span>
                        </div>
                        <div class="mt-4 grid grid-cols-1 gap-3 sm:grid-cols-2">
                            <div class="rounded-xl border border-[#E8EEF5] bg-[#F8FAFC] px-3 py-2">
                                <p class="text-[10px] font-black uppercase tracking-wider text-[#64748B]">Submission status</p>
                                <p id="detailPaperStatusLabel" class="mt-1 text-xs font-black text-[#111827]">—</p>
                            </div>
                            <div class="rounded-xl border border-[#E8EEF5] bg-[#F8FAFC] px-3 py-2">
                                <p class="text-[10px] font-black uppercase tracking-wider text-[#64748B]">Submitted at</p>
                                <p id="detailPaperSubmittedAt" class="mt-1 text-xs font-black text-[#111827]">—</p>
                            </div>
                        </div>
                        <a
                            id="detailPaperDownload"
                            href="#"
                            class="mt-4 inline-flex w-full items-center justify-center rounded-2xl bg-[#172233] px-4 py-3 text-center text-sm font-black uppercase tracking-wide text-white transition hover:bg-[#0B1220] sm:w-auto"
                        >
                            Download Paper
                        </a>
                    </div>
                </div>

                <div id="paperEmpty" class="mt-4 hidden rounded-2xl border border-dashed border-[#DDE6F2] bg-white px-4 py-4 text-center text-sm font-semibold text-[#64748B]">
                    No research paper has been uploaded for this participant yet.
                </div>
            </section>

            <div id="participantModalPendingActions" class="mt-5 hidden grid grid-cols-1 gap-4 md:grid-cols-2">
                <form id="rejectApplicationForm" method="POST" action="#">
                    @csrf
                    <button
                        type="submit"
                        id="rejectApplicationBtn"
                        class="h-14 w-full rounded-2xl border border-[#FCA5A5] bg-white text-sm font-black uppercase tracking-wide text-red-600 transition hover:bg-red-50"
                    >
                        Reject
                    </button>
                </form>

                <form id="approveApplicationForm" method="POST" action="#">
                    @csrf
                    <button
                        type="submit"
                        id="approveApplicationBtn"
                        class="flex h-14 w-full items-center justify-center gap-3 rounded-2xl bg-[#172233] text-sm font-black uppercase tracking-wide text-white transition hover:bg-[#0B1220]"
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
                class="mt-5 hidden rounded-2xl border border-[#86EFAC] bg-[#ECFDF5] px-5 py-4 text-center"
            >
                <div class="flex items-center justify-center gap-3 text-sm font-black uppercase tracking-wide text-[#047857]">
                    <svg class="h-6 w-6 shrink-0" fill="none" stroke="currentColor" stroke-width="2.3" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75l2 2 4-5"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9 9 0 100-18 9 9 0 000 18z"/>
                    </svg>
                    Registration Approved
                </div>
            </div>

            <div
                id="participantModalRejectedNotice"
                class="mt-5 hidden rounded-2xl border border-[#FECACA] bg-[#FEF2F2] px-5 py-4 text-center text-sm font-black uppercase tracking-wide text-[#B91C1C]"
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
            green: 'border-[#86EFAC] bg-[#ECFDF5] text-[#047857]',
            gold: 'border-[#FCD34D] bg-[#FFFBEB] text-[#D97706]',
            red: 'border-[#FECACA] bg-[#FEF2F2] text-[#B91C1C]',
        };

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
            const paper = participant.paper || {};

            if (isConference) {
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
            const canReview = registrationStatus === 'pending' && (!isConference || Boolean(paper.has_file));
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
