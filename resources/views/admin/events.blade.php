@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#F6F8FB] font-sans text-[#111827]">
    <div class="flex">

        {{-- SIDEBAR --}}
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

                    <a href="{{ route('admin.participants') }}" class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-bold text-[#53657F] hover:bg-[#F4F7FB]">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m8-4a4 4 0 10-8 0m8 0a4 4 0 01-8 0"/>
                        </svg>
                        Participants
                    </a>

                    <a href="{{ route('admin.evaluations') }}" class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-bold text-[#53657F] hover:bg-[#F4F7FB]">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 10h8M8 14h5M5 5h14v12H7l-4 4V7a2 2 0 012-2z"/>
                        </svg>
                        Evaluations
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

        {{-- MAIN CONTENT --}}
        <main class="ml-[270px] min-h-screen w-full">
            <header class="flex h-[78px] items-center justify-between border-b border-[#E5EAF1] bg-white px-9">
                <h2 class="text-sm font-black uppercase tracking-widest text-[#111827]">
                    Events
                </h2>

                <div class="flex items-center gap-4">
                    <button class="relative flex h-10 w-10 items-center justify-center rounded-2xl border border-[#DDE6F2] bg-white text-[#53657F]">
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

            <section class="px-8 py-9">
        @if (session('status'))
            <div class="mb-6 rounded-2xl border border-[#DDE6F2] bg-white px-5 py-4 text-sm font-semibold text-[#111827]">
                {{ session('status') }}
            </div>
        @endif

        {{-- PAGE HEADER --}}
        <div class="flex items-start justify-between gap-6">
            <div>
                <div class="flex items-center gap-3">
                    <div class="h-7 w-1 rounded-full bg-[#D2A64B]"></div>
                    <h1 class="text-[28px] font-black tracking-tight text-[#111827]">
                        EVENTS MANAGEMENT
                    </h1>
                </div>

                <p class="mt-2 text-sm text-[#53657F]">
                    Create, view, and organize school gatherings and conferences.
                </p>
            </div>

            <button
                type="button"
                id="openCreateEventModal"
                class="flex items-center gap-3 rounded-2xl bg-[#111827] px-7 py-4 text-sm font-black uppercase tracking-wide text-white shadow-sm transition hover:bg-[#0B1220]"
            >
                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                Create Event
            </button>
        </div>

        {{-- FILTER BAR --}}
        <section class="mt-16 rounded-2xl border border-[#DDE6F2] bg-white p-5">
            <div class="flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between">

                {{-- Search --}}
                <div class="relative w-full xl:w-[360px]">
                    <svg class="absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-[#9AA8BA]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m1.35-5.65a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>

                    <input
                        type="text"
                        placeholder="Search events..."
                        class="h-12 w-full rounded-2xl border border-[#DDE6F2] bg-[#F8FAFC] pl-12 pr-4 text-sm font-medium text-[#111827] outline-none placeholder:text-[#9AA8BA] focus:border-[#D2A64B] focus:ring-2 focus:ring-[#D2A64B]/20"
                    >
                </div>

                {{-- Filters --}}
                <div class="flex flex-wrap items-center gap-3">
                    <button
                        type="button"
                        class="flex h-12 w-12 items-center justify-center rounded-2xl text-[#8EA0B7] hover:bg-[#F8FAFC]"
                    >
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 4h18l-7 8v6l-4 2v-8L3 4z"/>
                        </svg>
                    </button>

                    <select class="h-12 rounded-2xl border border-[#DDE6F2] bg-[#F8FAFC] px-5 text-sm font-black text-[#111827] outline-none focus:border-[#D2A64B]">
                        <option>All Categories</option>
                        <option>School Event</option>
                        <option>Conference Event</option>
                    </select>

                    <select class="h-12 rounded-2xl border border-[#DDE6F2] bg-[#F8FAFC] px-5 text-sm font-black text-[#111827] outline-none focus:border-[#D2A64B]">
                        <option>All Months</option>
                        <option>January</option>
                        <option>February</option>
                        <option>March</option>
                        <option>April</option>
                        <option>May</option>
                        <option>June</option>
                        <option>July</option>
                        <option>August</option>
                        <option>September</option>
                        <option>October</option>
                        <option>November</option>
                        <option>December</option>
                    </select>

                    <select class="h-12 rounded-2xl border border-[#DDE6F2] bg-[#F8FAFC] px-5 text-sm font-black text-[#111827] outline-none focus:border-[#D2A64B]">
                        <option>All Years</option>
                        <option>2026</option>
                        <option>2025</option>
                        <option>2024</option>
                    </select>

                    {{-- View Toggle --}}
                    <div class="flex items-center gap-2 rounded-2xl border border-[#DDE6F2] bg-[#F8FAFC] p-1">
                        <button
                            type="button"
                            class="flex h-10 w-10 items-center justify-center rounded-xl bg-white text-[#111827] shadow-sm"
                            title="Grid View"
                        >
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 4h6v6H4V4zm10 0h6v6h-6V4zM4 14h6v6H4v-6zm10 0h6v6h-6v-6z"/>
                            </svg>
                        </button>

                        <button
                            type="button"
                            class="flex h-10 w-10 items-center justify-center rounded-xl text-[#A5B3C5] hover:bg-white"
                            title="List View"
                        >
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </section>

        {{-- EVENT GRID --}}
        <section class="mt-7 grid grid-cols-1 gap-7 lg:grid-cols-2 2xl:grid-cols-3">
            @foreach ($events as $event)
                @php
                    $eventDate = $event->event_date
                        ? $event->event_date->format('F j, Y')
                        : 'Date TBA';
                    $eventStartDateValue = $event->start_date ? $event->start_date->format('Y-m-d') : '';
                    $eventEndDateValue = $event->end_date ? $event->end_date->format('Y-m-d') : '';
                    $eventTypeRaw = strtolower((string) ($event->event_type ?? ''));
                    $isConference = str_contains($eventTypeRaw, 'conference');
                    $eventTypeLabel = $isConference ? 'Conference Event' : 'School Event';
                    $badgeStyleClass = $isConference ? 'bg-[#111827] text-white' : 'bg-white text-[#111827]';
                    $eventStatus = strtolower((string) ($event->status ?? 'active')) === 'archived' ? 'archived' : 'active';
                    $statusStyleClass = $eventStatus === 'archived' ? 'bg-[#9CA3AF]' : 'bg-[#00C781]';
                    $bannerImage = $event->banner_url ?: 'https://images.unsplash.com/photo-1511578314322-379afb476865?auto=format&fit=crop&w=1200&q=60';
                    $reminderSummary = $eventReminderSummary[$event->event_id] ?? null;
                    $evaluationRecipientCount = (int) ($reminderSummary['total_recipients'] ?? 0);
                @endphp
                <article class="overflow-hidden rounded-2xl border border-[#DDE6F2] bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-md">

                    {{-- Image --}}
                    <div class="relative h-[190px] overflow-hidden">
                        <img
                            src="{{ $bannerImage }}"
                            alt="{{ $event->event_name }}"
                            class="h-full w-full object-cover"
                        >

                        <div class="absolute inset-0 bg-gradient-to-t from-black/45 via-black/5 to-black/10"></div>

                        {{-- Type Badge --}}
                        <span class="absolute left-4 top-4 rounded-xl px-4 py-2 text-xs font-black uppercase tracking-wide {{ $badgeStyleClass }}">
                            {{ $eventTypeLabel }}
                        </span>

                        {{-- Status --}}
                        <span class="absolute bottom-4 right-4 rounded-xl {{ $statusStyleClass }} px-4 py-2 text-xs font-black text-white">
                            • {{ ucfirst($eventStatus) }}
                        </span>
                    </div>

                    {{-- Body --}}
                    <div class="p-6">
                        <h2 class="line-clamp-1 text-xl font-black uppercase tracking-tight text-[#111827]">
                            {{ $event->event_name }}
                        </h2>

                        <div class="mt-5 space-y-2 text-sm font-medium text-[#64748B]">
                            <div class="flex items-center gap-3">
                                <svg class="h-4 w-4 text-[#D2A64B]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3M4 11h16M5 5h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z"/>
                                </svg>
                                {{ $eventDate }}
                            </div>

                            <div class="flex items-center gap-3">
                                <svg class="h-4 w-4 text-[#D2A64B]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 21s7-4.438 7-11a7 7 0 10-14 0c0 6.562 7 11 7 11z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 10a2 2 0 100-4 2 2 0 000 4z"/>
                                </svg>
                                {{ $event->location ?: 'Location TBA' }}
                            </div>
                        </div>

                        <div class="mt-5 border-t border-[#E8EEF5] pt-5">
                            <div class="flex justify-end">
                                <button
                                    type="button"
                                    class="rounded-2xl border border-[#DDE6F2] bg-[#F8FAFC] px-5 py-2 text-sm font-black uppercase tracking-wide text-[#111827] transition hover:bg-[#EEF3F9]"
                                    data-manage-event-trigger
                                    data-event-id="{{ $event->event_id }}"
                                    data-event-title="{{ $event->event_name }}"
                                    data-event-type="{{ $eventTypeLabel }}"
                                    data-event-date="{{ $eventDate }}"
                                    data-event-location="{{ $event->location ?: 'Location TBA' }}"
                                    data-event-image="{{ $bannerImage }}"
                                    data-event-status="{{ ucfirst($eventStatus) }}"
                                    data-event-type-value="{{ $event->event_type ?: 'School Event' }}"
                                    data-event-hosted-by="{{ $event->hosted_by ?: '' }}"
                                    data-event-attendance-format="{{ $event->attendance_format ?: 'Face-to-Face' }}"
                                    data-event-start-date="{{ $eventStartDateValue }}"
                                    data-event-end-date="{{ $eventEndDateValue }}"
                                    data-event-description="{{ $event->description ?: '' }}"
                                    data-event-update-url="{{ route('admin.events.update', ['event' => $event->event_id]) }}"
                                    data-event-archive-url="{{ route('admin.events.archive', ['event' => $event->event_id]) }}"
                                    data-send-evaluation-url="{{ route('admin.events.send-evaluation-reminder', ['event' => $event->event_id]) }}"
                                    data-evaluation-recipient-count="{{ $evaluationRecipientCount }}"
                                >
                                    Manage Event ->
                                </button>
                            </div>
                        </div>
                    </div>
                </article>
            @endforeach

            @if ($events->isEmpty())
                <article class="col-span-full rounded-2xl border border-dashed border-[#DDE6F2] bg-white p-10 text-center">
                    <h3 class="text-xl font-black text-[#111827]">No events found</h3>
                    <p class="mt-2 text-sm font-medium text-[#64748B]">
                        Wala pang event records sa system.
                    </p>
                </article>
            @endif
        </section>

        {{-- CREATE EVENT MODAL --}}
        <div
            id="createEventModal"
            class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 px-4 py-6 backdrop-blur-sm"
        >
            <div class="relative flex max-h-[92vh] w-full max-w-[760px] flex-col overflow-hidden rounded-3xl border border-[#DDE6F2] bg-white shadow-2xl">

                {{-- Modal Header --}}
                <div class="flex items-start justify-between bg-[#172233] px-6 py-6 text-white">
                    <div>
                        <h2 class="text-2xl font-black uppercase tracking-wide">
                            Create New Event
                        </h2>
                        <p class="mt-2 text-sm text-white/70">
                            Fill in the details for the upcoming event.
                        </p>
                    </div>

                    <button
                        type="button"
                        onclick="closeCreateEventModal()"
                        class="flex h-10 w-10 items-center justify-center rounded-full bg-white/10 text-white transition hover:bg-white/20"
                    >
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2.3" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                {{-- Form --}}
                <form
                    action="{{ route('admin.events.store') }}"
                    method="POST"
                    enctype="multipart/form-data"
                    class="overflow-y-auto px-6 py-6"
                >
                    @csrf

                    {{-- BASIC INFO --}}
                    <section class="rounded-2xl border border-[#DDE6F2] bg-[#F8FAFC] p-6">
                        <h3 class="text-base font-black uppercase tracking-wide text-[#111827]">
                            Basic Info
                        </h3>

                        <div class="mt-4 border-t border-[#DDE6F2] pt-5">

                            {{-- Event Type --}}
                            <label class="mb-3 block text-sm font-black uppercase tracking-widest text-[#64748B]">
                                Event Type
                            </label>

                            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                <label class="cursor-pointer">
                                    <input type="radio" name="event_type" value="School Event" class="peer hidden" @checked(old('event_type', 'Conference') === 'School Event')>
                                    <div class="rounded-2xl border-2 border-[#DDE6F2] bg-white px-5 py-5 text-center transition peer-checked:border-[#111827] peer-checked:bg-white">
                                        <p class="text-base font-black uppercase tracking-wide text-[#64748B] peer-checked:text-[#111827]">
                                            School Event
                                        </p>
                                        <p class="mt-2 text-sm text-[#7B8AA0]">
                                            Campus activities
                                        </p>
                                    </div>
                                </label>

                                <label class="cursor-pointer">
                                    <input type="radio" name="event_type" value="Conference" class="peer hidden" @checked(old('event_type', 'Conference') === 'Conference')>
                                    <div class="rounded-2xl border-2 border-[#DDE6F2] bg-white px-5 py-5 text-center transition peer-checked:border-[#111827] peer-checked:bg-white">
                                        <p class="text-base font-black uppercase tracking-wide text-[#111827]">
                                            Conference Event
                                        </p>
                                        <p class="mt-2 text-sm text-[#7B8AA0]">
                                            Requires PDF paper
                                        </p>
                                    </div>
                                </label>
                            </div>
                            @error('event_type') <p class="mt-2 text-xs font-semibold text-[#DC2626]">{{ $message }}</p> @enderror

                            {{-- Event Title --}}
                            <div class="mt-5">
                                <label id="eventTitleLabel" class="mb-3 block text-sm font-black uppercase tracking-widest text-[#64748B]">
                                    {{ old('event_type', 'Conference') === 'School Event' ? 'School Event Title' : 'Conference Event Title' }}
                                </label>

                                <input
                                    type="text"
                                    name="event_name"
                                    value="{{ old('event_name') }}"
                                    placeholder="e.g. Tech Innovations Summit"
                                    class="h-14 w-full rounded-2xl border border-[#DDE6F2] bg-white px-5 text-base text-[#111827] outline-none placeholder:text-[#9AA8BA] focus:border-[#D2A64B] focus:ring-2 focus:ring-[#D2A64B]/20"
                                >
                                @error('event_name') <p class="mt-2 text-xs font-semibold text-[#DC2626]">{{ $message }}</p> @enderror
                            </div>

                            {{-- Hosted By --}}
                            <div class="mt-5">
                                <label class="mb-3 block text-sm font-black uppercase tracking-widest text-[#64748B]">
                                    Hosted By / Department
                                </label>

                                <input
                                    type="text"
                                    name="hosted_by"
                                    value="{{ old('hosted_by') }}"
                                    placeholder="e.g. College of Computer Studies"
                                    class="h-14 w-full rounded-2xl border border-[#DDE6F2] bg-white px-5 text-base text-[#111827] outline-none placeholder:text-[#9AA8BA] focus:border-[#D2A64B] focus:ring-2 focus:ring-[#D2A64B]/20"
                                >
                                @error('hosted_by') <p class="mt-2 text-xs font-semibold text-[#DC2626]">{{ $message }}</p> @enderror
                            </div>

                            {{-- Attendance Format --}}
                            <div class="mt-5">
                                <label class="mb-3 block text-sm font-black uppercase tracking-widest text-[#64748B]">
                                    Attendance Format
                                </label>

                                <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                                    <label class="cursor-pointer">
                                        <input type="radio" name="attendance_format" value="Online" class="peer hidden" @checked(old('attendance_format', 'Hybrid') === 'Online')>
                                        <div class="rounded-2xl border-2 border-[#DDE6F2] bg-white px-5 py-4 text-center text-sm font-black uppercase tracking-wide text-[#64748B] transition peer-checked:border-[#111827] peer-checked:text-[#111827]">
                                            Online
                                        </div>
                                    </label>

                                    <label class="cursor-pointer">
                                        <input type="radio" name="attendance_format" value="Face-to-Face" class="peer hidden" @checked(old('attendance_format', 'Hybrid') === 'Face-to-Face')>
                                        <div class="rounded-2xl border-2 border-[#DDE6F2] bg-white px-5 py-4 text-center text-sm font-black uppercase tracking-wide text-[#64748B] transition peer-checked:border-[#111827] peer-checked:text-[#111827]">
                                            Face-to-Face
                                        </div>
                                    </label>

                                    <label class="cursor-pointer">
                                        <input type="radio" name="attendance_format" value="Hybrid" class="peer hidden" @checked(old('attendance_format', 'Hybrid') === 'Hybrid')>
                                        <div class="rounded-2xl border-2 border-[#DDE6F2] bg-white px-5 py-4 text-center text-sm font-black uppercase tracking-wide text-[#64748B] transition peer-checked:border-[#111827] peer-checked:text-[#111827]">
                                            Hybrid
                                        </div>
                                    </label>
                                </div>
                                @error('attendance_format') <p class="mt-2 text-xs font-semibold text-[#DC2626]">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </section>

                    {{-- SCHEDULE & LOCATION --}}
                    <section class="mt-6 rounded-2xl border border-[#DDE6F2] bg-[#F8FAFC] p-6">
                        <h3 class="text-base font-black uppercase tracking-wide text-[#111827]">
                            Schedule & Location
                        </h3>

                        <div class="mt-4 border-t border-[#DDE6F2] pt-5">
                            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">

                                <div>
                                    <label class="mb-3 flex items-center gap-1 text-sm font-black uppercase tracking-widest text-[#64748B]">
                                        <span class="text-[#D2A64B]">□</span>
                                        Start Date
                                    </label>

                                    <input
                                        type="date"
                                        name="start_date"
                                        value="{{ old('start_date') }}"
                                        class="h-14 w-full rounded-2xl border border-[#DDE6F2] bg-white px-5 text-base text-[#111827] outline-none focus:border-[#D2A64B] focus:ring-2 focus:ring-[#D2A64B]/20"
                                    >
                                    @error('start_date') <p class="mt-2 text-xs font-semibold text-[#DC2626]">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label class="mb-3 flex items-center gap-1 text-sm font-black uppercase tracking-widest text-[#64748B]">
                                        <span class="text-[#D2A64B]">□</span>
                                        End Date
                                    </label>

                                    <input
                                        type="date"
                                        name="end_date"
                                        value="{{ old('end_date') }}"
                                        class="h-14 w-full rounded-2xl border border-[#DDE6F2] bg-white px-5 text-base text-[#111827] outline-none focus:border-[#D2A64B] focus:ring-2 focus:ring-[#D2A64B]/20"
                                    >
                                    @error('end_date') <p class="mt-2 text-xs font-semibold text-[#DC2626]">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label class="mb-3 flex items-center gap-1 text-sm font-black uppercase tracking-widest text-[#64748B]">
                                        <span class="text-[#D2A64B]">⌖</span>
                                        Location
                                    </label>

                                    <input
                                        type="text"
                                        name="location"
                                        value="{{ old('location') }}"
                                        placeholder="e.g. Main Auditorium"
                                        class="h-14 w-full rounded-2xl border border-[#DDE6F2] bg-white px-5 text-base text-[#111827] outline-none placeholder:text-[#9AA8BA] focus:border-[#D2A64B] focus:ring-2 focus:ring-[#D2A64B]/20"
                                    >
                                    @error('location') <p class="mt-2 text-xs font-semibold text-[#DC2626]">{{ $message }}</p> @enderror
                                </div>

                            </div>
                        </div>
                    </section>

                    {{-- CONTENT & MEDIA --}}
                    <section class="mt-6 rounded-2xl border border-[#DDE6F2] bg-[#F8FAFC] p-6">
                        <h3 class="text-base font-black uppercase tracking-wide text-[#111827]">
                            Content & Media
                        </h3>

                        <div class="mt-4 border-t border-[#DDE6F2] pt-5">

                            {{-- Description --}}
                            <div>
                                <label class="mb-3 flex items-center gap-2 text-sm font-black uppercase tracking-widest text-[#64748B]">
                                    <span class="text-[#D2A64B]">☰</span>
                                    Full Description
                                </label>

                                <textarea
                                    name="description"
                                    rows="4"
                                    placeholder="Write detailed information..."
                                    class="w-full resize-none rounded-2xl border border-[#DDE6F2] bg-white px-5 py-4 text-base text-[#111827] outline-none placeholder:text-[#9AA8BA] focus:border-[#D2A64B] focus:ring-2 focus:ring-[#D2A64B]/20"
                                >{{ old('description') }}</textarea>
                                @error('description') <p class="mt-2 text-xs font-semibold text-[#DC2626]">{{ $message }}</p> @enderror
                            </div>

                            {{-- Banner Upload --}}
                            <div class="mt-6">
                                <label class="mb-3 flex items-center gap-2 text-sm font-black uppercase tracking-widest text-[#64748B]">
                                    <span class="text-[#D2A64B]">▧</span>
                                    Event Banner Image
                                </label>

                                <label class="flex min-h-[180px] cursor-pointer flex-col items-center justify-center rounded-2xl border-2 border-dashed border-[#E3CFA4] bg-white px-6 py-8 text-center transition hover:bg-[#FFF8EA]">
                                    <input
                                        type="file"
                                        name="banner_image"
                                        accept="image/png,image/jpeg,image/jpg,image/webp"
                                        class="hidden"
                                    >

                                    <svg class="h-12 w-12 text-[#C8A25A]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01.88-7.903A5 5 0 1117.9 9H18a4 4 0 010 8h-1m-5-4v8m0 0l-3-3m3 3l3-3"/>
                                    </svg>

                                    <p class="mt-3 text-base font-black text-[#111827]">
                                        Upload image
                                    </p>

                                    <p class="mt-2 text-sm text-[#7B8AA0]">
                                        PNG, JPG, WEBP up to 5MB
                                    </p>
                                </label>
                                @error('banner_image') <p class="mt-2 text-xs font-semibold text-[#DC2626]">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </section>

                    {{-- ACTION BUTTONS --}}
                    <div class="sticky bottom-0 mt-6 grid grid-cols-1 gap-4 bg-white pt-4 md:grid-cols-[220px_1fr]">
                        <button
                            type="button"
                            onclick="closeCreateEventModal()"
                            class="h-14 rounded-2xl border border-[#DDE6F2] bg-[#F8FAFC] text-sm font-black uppercase tracking-wide text-[#111827] transition hover:bg-[#EEF3F9]"
                        >
                            Cancel
                        </button>

                        <button
                            type="submit"
                            class="h-14 rounded-2xl bg-[#172233] text-sm font-black uppercase tracking-wide text-white transition hover:bg-[#0B1220]"
                        >
                            Publish Event
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- MANAGE EVENT MODAL --}}
        <div
            id="manageEventModal"
            class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 px-4 py-6 backdrop-blur-sm"
        >
            <div class="relative flex max-h-[92vh] w-full max-w-[760px] flex-col overflow-hidden rounded-3xl border border-[#DDE6F2] bg-white shadow-2xl">

                {{-- MODAL HEADER --}}
                <div class="flex items-center justify-between bg-[#172233] px-7 py-6 text-white">
                    <div class="flex items-center gap-3">
                        <svg class="h-6 w-6 text-[#D2B06A]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3M4 11h16M5 5h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z"/>
                        </svg>

                        <h2 class="text-2xl font-black uppercase tracking-wide">
                            Manage Event
                        </h2>
                    </div>

                    <button
                        type="button"
                        onclick="closeManageEventModal()"
                        class="flex h-10 w-10 items-center justify-center rounded-full bg-white/10 text-white transition hover:bg-white/20"
                    >
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2.3" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                {{-- MODAL BODY --}}
                <div class="overflow-y-auto px-7 py-7">

                    {{-- EVENT HERO --}}
                    <div
                        id="manageEventHero"
                        class="relative overflow-hidden rounded-2xl bg-[#172233] p-6 text-white"
                        style="background-size: cover; background-position: center;"
                    >
                        <div class="flex items-center justify-between gap-5">
                            <div class="flex items-center gap-5">
                                <img
                                    id="manageEventImageThumb"
                                    src=""
                                    alt="Event image"
                                    class="h-20 w-20 rounded-2xl border border-white/20 object-cover"
                                >

                                <div>
                                    <span id="manageEventTypeBadge" class="inline-flex rounded-xl bg-white/10 px-4 py-2 text-xs font-black uppercase tracking-wide text-[#D2B06A]">
                                        School Event
                                    </span>

                                    <h3 id="manageEventTitle" class="mt-3 text-xl font-black uppercase tracking-wide">
                                        Event Title
                                    </h3>

                                    <div class="mt-2 flex flex-wrap items-center gap-4 text-sm text-white/85">
                                        <div class="flex items-center gap-2">
                                            <svg class="h-4 w-4 text-[#D2B06A]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3M4 11h16M5 5h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z"/>
                                            </svg>
                                            <span id="manageEventDate">Date TBA</span>
                                        </div>

                                        <div class="flex items-center gap-2">
                                            <svg class="h-4 w-4 text-[#D2B06A]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 21s7-4.438 7-11a7 7 0 10-14 0c0 6.562 7 11 7 11z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 10a2 2 0 100-4 2 2 0 000 4z"/>
                                            </svg>
                                            <span id="manageEventLocation">Location TBA</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <span id="manageEventStatusBadge" class="shrink-0 rounded-xl bg-[#00C781] px-5 py-3 text-sm font-black text-white">
                                • Active
                            </span>
                        </div>
                    </div>

                    {{-- EDIT DETAILS BUTTON --}}
                    <div class="mt-7 flex justify-end">
                        <button
                            id="openEditEventModalFromManage"
                            type="button"
                            class="flex items-center gap-3 rounded-2xl border border-[#DDE6F2] bg-[#F8FAFC] px-7 py-3 text-sm font-black uppercase tracking-wide text-[#111827] transition hover:bg-[#EEF3F9]"
                        >
                            <svg class="h-5 w-5 text-[#C8A25A]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 7h16M4 12h10M4 17h7"/>
                            </svg>
                            Edit Details
                        </button>
                    </div>

                    {{-- POST-EVENT CONTROLS --}}
                    <section class="mt-7 overflow-hidden rounded-2xl border border-[#DDE6F2] bg-white">
                        <div class="border-b border-[#DDE6F2] bg-[#F8FAFC] px-7 py-6">
                            <div class="flex items-start gap-3">
                                <svg class="mt-1 h-5 w-5 text-[#D2B06A]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15l-3.5 2 1-4L6 10h4l2-4 2 4h4l-3.5 3 1 4L12 15z"/>
                                </svg>

                                <div>
                                    <h3 class="text-xl font-black uppercase tracking-wide text-[#111827]">
                                        Post-Event Controls
                                    </h3>
                                    <p class="mt-1 text-sm text-[#53657F]">
                                        Manage post-event actions like evaluations and certificates.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col items-center justify-center px-8 py-10 text-center">
                            <svg class="h-16 w-16 text-[#C8A25A]" fill="none" stroke="currentColor" stroke-width="2.3" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 9a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l-1 9 4-3 4 3-1-9"/>
                            </svg>

                            <h4 class="mt-5 text-xl font-black uppercase tracking-wide text-[#111827]">
                                Event Concluded?
                            </h4>

                            <p class="mt-3 max-w-[520px] text-sm leading-6 text-[#53657F]">
                                Initiate the evaluation process and generate certificates automatically based on attendance and feedback completion.
                            </p>

                            <button
                                id="manageSendEvaluationButton"
                                type="button"
                                class="mt-7 flex items-center gap-3 rounded-2xl bg-[#D2B06A] px-10 py-4 text-sm font-black uppercase tracking-wide text-white transition hover:bg-[#C19D52]"
                            >
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 10h8M8 14h5M5 5h14v12H7l-4 4V7a2 2 0 012-2z"/>
                                </svg>
                                Send Evaluation Links
                            </button>
                        </div>
                    </section>

                </div>

                {{-- MODAL FOOTER --}}
                <div class="flex justify-end border-t border-[#DDE6F2] bg-white px-7 py-5">
                    <button
                        type="button"
                        onclick="closeManageEventModal()"
                        class="rounded-2xl border border-[#DDE6F2] bg-[#F8FAFC] px-8 py-3 text-sm font-black uppercase tracking-wide text-[#111827] transition hover:bg-[#EEF3F9]"
                    >
                        Close
                    </button>
                </div>
            </div>
        </div>

        {{-- EDIT EVENT MODAL --}}
        <div
            id="editEventModal"
            class="fixed inset-0 z-[60] hidden items-center justify-center bg-black/50 px-4 py-6 backdrop-blur-sm"
        >
            <div class="relative flex max-h-[92vh] w-full max-w-[760px] flex-col overflow-hidden rounded-3xl border border-[#DDE6F2] bg-white shadow-2xl">

                {{-- MODAL HEADER --}}
                <div class="flex items-start justify-between bg-[#172233] px-7 py-6 text-white">
                    <div>
                        <h2 class="text-2xl font-black uppercase tracking-wide">
                            Edit Event
                        </h2>
                        <p id="editEventHeaderText" class="mt-2 text-sm text-white/70">
                            Update the details for: Event Title
                        </p>
                    </div>

                    <button
                        type="button"
                        onclick="closeEditEventModal()"
                        class="flex h-10 w-10 items-center justify-center rounded-full bg-white/10 text-white transition hover:bg-white/20"
                    >
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2.3" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                {{-- FORM --}}
                <form
                    id="editEventForm"
                    action="#"
                    method="POST"
                    enctype="multipart/form-data"
                    class="overflow-y-auto px-7 py-7"
                >
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="editEventIdInput" name="editing_event_id" value="">

                    {{-- BASIC INFO --}}
                    <section class="rounded-2xl border border-[#DDE6F2] bg-[#F8FAFC] p-6">
                        <h3 class="text-base font-black uppercase tracking-wide text-[#111827]">
                            Basic Info
                        </h3>

                        <div class="mt-4 border-t border-[#DDE6F2] pt-5">
                            <label class="mb-3 block text-sm font-black uppercase tracking-widest text-[#64748B]">
                                Event Type
                            </label>

                            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                <label class="cursor-pointer">
                                    <input type="radio" name="event_type" value="School Event" class="peer hidden">
                                    <div class="rounded-2xl border-2 border-[#DDE6F2] bg-white px-5 py-5 text-center transition peer-checked:border-[#111827]">
                                        <p class="text-base font-black uppercase tracking-wide text-[#111827]">
                                            School Event
                                        </p>
                                        <p class="mt-2 text-sm text-[#7B8AA0]">
                                            Campus activities
                                        </p>
                                    </div>
                                </label>

                                <label class="cursor-pointer">
                                    <input type="radio" name="event_type" value="Conference" class="peer hidden">
                                    <div class="rounded-2xl border-2 border-[#DDE6F2] bg-white px-5 py-5 text-center transition peer-checked:border-[#111827]">
                                        <p class="text-base font-black uppercase tracking-wide text-[#64748B]">
                                            Conference Event
                                        </p>
                                        <p class="mt-2 text-sm text-[#7B8AA0]">
                                            Requires PDF paper
                                        </p>
                                    </div>
                                </label>
                            </div>
                            @error('event_type', 'editEvent') <p class="mt-2 text-xs font-semibold text-[#DC2626]">{{ $message }}</p> @enderror

                            <div class="mt-5">
                                <label id="editEventTitleLabel" class="mb-3 block text-sm font-black uppercase tracking-widest text-[#64748B]">
                                    Event Title
                                </label>

                                <input
                                    type="text"
                                    id="editEventNameInput"
                                    name="event_name"
                                    value="{{ old('event_name') }}"
                                    class="h-14 w-full rounded-2xl border border-[#DDE6F2] bg-white px-5 text-base text-[#111827] outline-none focus:border-[#D2A64B] focus:ring-2 focus:ring-[#D2A64B]/20"
                                >
                                @error('event_name', 'editEvent') <p class="mt-2 text-xs font-semibold text-[#DC2626]">{{ $message }}</p> @enderror
                            </div>

                            <div class="mt-5">
                                <label class="mb-3 block text-sm font-black uppercase tracking-widest text-[#64748B]">
                                    Hosted By / Department
                                </label>

                                <input
                                    type="text"
                                    id="editHostedByInput"
                                    name="hosted_by"
                                    value="{{ old('hosted_by') }}"
                                    class="h-14 w-full rounded-2xl border border-[#DDE6F2] bg-white px-5 text-base text-[#111827] outline-none focus:border-[#D2A64B] focus:ring-2 focus:ring-[#D2A64B]/20"
                                >
                                @error('hosted_by', 'editEvent') <p class="mt-2 text-xs font-semibold text-[#DC2626]">{{ $message }}</p> @enderror
                            </div>

                            <div class="mt-5">
                                <label class="mb-3 block text-sm font-black uppercase tracking-widest text-[#64748B]">
                                    Attendance Format
                                </label>

                                <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                                    <label class="cursor-pointer">
                                        <input type="radio" name="attendance_format" value="Online" class="peer hidden">
                                        <div class="rounded-2xl border-2 border-[#DDE6F2] bg-white px-5 py-4 text-center text-sm font-black uppercase tracking-wide text-[#64748B] transition peer-checked:border-[#111827] peer-checked:text-[#111827]">
                                            Online
                                        </div>
                                    </label>

                                    <label class="cursor-pointer">
                                        <input type="radio" name="attendance_format" value="Face-to-Face" class="peer hidden">
                                        <div class="rounded-2xl border-2 border-[#DDE6F2] bg-white px-5 py-4 text-center text-sm font-black uppercase tracking-wide text-[#64748B] transition peer-checked:border-[#111827] peer-checked:text-[#111827]">
                                            Face-to-Face
                                        </div>
                                    </label>

                                    <label class="cursor-pointer">
                                        <input type="radio" name="attendance_format" value="Hybrid" class="peer hidden">
                                        <div class="rounded-2xl border-2 border-[#DDE6F2] bg-white px-5 py-4 text-center text-sm font-black uppercase tracking-wide text-[#64748B] transition peer-checked:border-[#111827] peer-checked:text-[#111827]">
                                            Hybrid
                                        </div>
                                    </label>
                                </div>
                                @error('attendance_format', 'editEvent') <p class="mt-2 text-xs font-semibold text-[#DC2626]">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </section>

                    {{-- SCHEDULE & LOCATION --}}
                    <section class="mt-6 rounded-2xl border border-[#DDE6F2] bg-[#F8FAFC] p-6">
                        <h3 class="text-base font-black uppercase tracking-wide text-[#111827]">
                            Schedule & Location
                        </h3>

                        <div class="mt-4 border-t border-[#DDE6F2] pt-5">
                            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                                <div>
                                    <label class="mb-3 flex items-center gap-1 text-sm font-black uppercase tracking-widest text-[#64748B]">
                                        <svg class="h-4 w-4 text-[#D2A64B]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3M4 11h16M5 5h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z"/>
                                        </svg>
                                        Start Date
                                    </label>

                                    <input
                                        type="date"
                                        id="editStartDateInput"
                                        name="start_date"
                                        value="{{ old('start_date') }}"
                                        class="h-14 w-full rounded-2xl border border-[#DDE6F2] bg-white px-5 text-base text-[#111827] outline-none focus:border-[#D2A64B] focus:ring-2 focus:ring-[#D2A64B]/20"
                                    >
                                    @error('start_date', 'editEvent') <p class="mt-2 text-xs font-semibold text-[#DC2626]">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label class="mb-3 flex items-center gap-1 text-sm font-black uppercase tracking-widest text-[#64748B]">
                                        <svg class="h-4 w-4 text-[#D2A64B]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3M4 11h16M5 5h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z"/>
                                        </svg>
                                        End Date
                                    </label>

                                    <input
                                        type="date"
                                        id="editEndDateInput"
                                        name="end_date"
                                        value="{{ old('end_date') }}"
                                        class="h-14 w-full rounded-2xl border border-[#DDE6F2] bg-white px-5 text-base text-[#111827] outline-none focus:border-[#D2A64B] focus:ring-2 focus:ring-[#D2A64B]/20"
                                    >
                                    @error('end_date', 'editEvent') <p class="mt-2 text-xs font-semibold text-[#DC2626]">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label class="mb-3 flex items-center gap-1 text-sm font-black uppercase tracking-widest text-[#64748B]">
                                        <svg class="h-4 w-4 text-[#D2A64B]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 21s7-4.438 7-11a7 7 0 10-14 0c0 6.562 7 11 7 11z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 10a2 2 0 100-4 2 2 0 000 4z"/>
                                        </svg>
                                        Location
                                    </label>

                                    <input
                                        type="text"
                                        id="editLocationInput"
                                        name="location"
                                        value="{{ old('location') }}"
                                        class="h-14 w-full rounded-2xl border border-[#DDE6F2] bg-white px-5 text-base text-[#111827] outline-none focus:border-[#D2A64B] focus:ring-2 focus:ring-[#D2A64B]/20"
                                    >
                                    @error('location', 'editEvent') <p class="mt-2 text-xs font-semibold text-[#DC2626]">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- CONTENT & MEDIA --}}
                    <section class="mt-6 rounded-2xl border border-[#DDE6F2] bg-[#F8FAFC] p-6">
                        <h3 class="text-base font-black uppercase tracking-wide text-[#111827]">
                            Content & Media
                        </h3>

                        <div class="mt-4 border-t border-[#DDE6F2] pt-5">
                            <div>
                                <label class="mb-3 flex items-center gap-2 text-sm font-black uppercase tracking-widest text-[#64748B]">
                                    <svg class="h-4 w-4 text-[#D2A64B]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 7h16M4 12h10M4 17h7"/>
                                    </svg>
                                    Description
                                </label>

                                <textarea
                                    id="editDescriptionInput"
                                    name="description"
                                    rows="4"
                                    class="w-full resize-none rounded-2xl border border-[#DDE6F2] bg-white px-5 py-4 text-base leading-7 text-[#111827] outline-none focus:border-[#D2A64B] focus:ring-2 focus:ring-[#D2A64B]/20"
                                >{{ old('description') }}</textarea>
                                @error('description', 'editEvent') <p class="mt-2 text-xs font-semibold text-[#DC2626]">{{ $message }}</p> @enderror
                            </div>

                            <div class="mt-6">
                                <label class="relative flex min-h-[130px] cursor-pointer flex-col items-center justify-center overflow-hidden rounded-2xl border-2 border-dashed border-[#E3CFA4] bg-white px-6 py-8 text-center transition hover:bg-[#FFF8EA]">
                                    <input
                                        type="file"
                                        name="banner_image"
                                        accept="image/png,image/jpeg,image/jpg,image/webp"
                                        class="hidden"
                                    >

                                    <img
                                        id="editCurrentImagePreview"
                                        src=""
                                        alt="Current Event Banner"
                                        class="absolute inset-0 h-full w-full object-cover opacity-20"
                                    >

                                    <div class="relative z-10 flex flex-col items-center">
                                        <svg class="h-12 w-12 text-[#C8A25A]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01.88-7.903A5 5 0 1117.9 9H18a4 4 0 010 8h-1m-5-4v8m0 0l-3-3m3 3l3-3"/>
                                        </svg>

                                        <p class="mt-3 text-base font-black text-[#111827]">
                                            Replace image
                                        </p>
                                    </div>
                                </label>
                                @error('banner_image', 'editEvent') <p class="mt-2 text-xs font-semibold text-[#DC2626]">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </section>

                    {{-- ACTION BUTTONS --}}
                    <div class="sticky bottom-0 mt-7 grid grid-cols-1 gap-4 bg-white pt-4 md:grid-cols-[180px_180px_1fr]">
                        <button
                            type="button"
                            onclick="closeEditEventModal()"
                            class="h-14 rounded-2xl border border-[#DDE6F2] bg-[#F8FAFC] text-sm font-black uppercase tracking-wide text-[#111827] transition hover:bg-[#EEF3F9]"
                        >
                            Cancel
                        </button>

                        <button
                            id="editArchiveButton"
                            type="button"
                            class="flex h-14 items-center justify-center gap-2 rounded-2xl border border-[#DDE6F2] bg-white text-sm font-black uppercase tracking-wide text-[#64748B] transition hover:bg-[#F8FAFC]"
                        >
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 8h14M8 8V6a2 2 0 012-2h4a2 2 0 012 2v2m1 0v12a2 2 0 01-2 2H7a2 2 0 01-2-2V8h14z"/>
                            </svg>
                            Archive
                        </button>

                        <button
                            type="submit"
                            class="h-14 rounded-2xl bg-[#172233] text-sm font-black uppercase tracking-wide text-white transition hover:bg-[#0B1220]"
                        >
                            Save Changes
                        </button>
                    </div>
                </form>

                <form id="editArchiveForm" method="POST" action="#" class="hidden">
                    @csrf
                    @method('PATCH')
                </form>
            </div>
        </div>

        {{-- CERTIFICATION LOGIC MODAL --}}
        <div
            id="certificationLogicModal"
            class="fixed inset-0 z-[70] hidden items-center justify-center bg-black/60 px-4 py-6 backdrop-blur-sm"
        >
            <div class="relative flex max-h-[92vh] w-full max-w-[650px] flex-col overflow-hidden rounded-3xl border border-[#DDE6F2] bg-white shadow-2xl">

                {{-- HEADER --}}
                <div class="border-b-4 border-[#D2B06A] bg-[#172233] px-6 py-6 text-white">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <div class="flex items-center gap-3">
                                <svg class="h-6 w-6 text-[#D2B06A]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.957a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.367 2.446a1 1 0 00-.364 1.118l1.286 3.957c.3.921-.755 1.688-1.538 1.118l-3.367-2.446a1 1 0 00-1.176 0l-3.367 2.446c-.783.57-1.838-.197-1.538-1.118l1.286-3.957a1 1 0 00-.364-1.118L4.06 9.384c-.783-.57-.38-1.81.588-1.81H8.81a1 1 0 00.95-.69l1.286-3.957z"/>
                                </svg>

                                <h2 class="text-xl font-black uppercase tracking-wide">
                                    Certification Logic
                                </h2>
                            </div>

                            <p class="mt-2 text-sm text-white/75">
                                Review the automated certificate distribution flow
                            </p>
                        </div>

                        <button
                            type="button"
                            onclick="closeCertificationLogicModal()"
                            class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-white/10 text-white transition hover:bg-white/20"
                        >
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2.3" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- BODY --}}
                <div class="overflow-y-auto px-7 py-7">
                    <p class="text-center text-sm font-black text-[#111827]">
                        Based on system rules, certificates will be issued dynamically:
                    </p>

                    <div class="relative mt-6 grid grid-cols-1 gap-5 md:grid-cols-2">
                        <div class="absolute left-1/2 top-1/2 hidden h-[3px] w-8 -translate-x-1/2 -translate-y-1/2 bg-[#DDE6F2] md:block"></div>

                        <section class="relative rounded-2xl border border-[#DDE6F2] bg-[#F8FAFC] p-6">
                            <div class="flex h-11 w-11 items-center justify-center rounded-2xl border border-[#FDBA3B] bg-[#FFF8EA] text-[#F97316]">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2.3" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9 9 0 100-18 9 9 0 000 18z"/>
                                </svg>
                            </div>

                            <h3 class="mt-5 text-base font-black uppercase tracking-wide text-[#1F2937]">
                                Did Not Evaluate
                            </h3>

                            <p class="mt-2 text-sm leading-6 text-[#64748B]">
                                Participant attended but did not submit an evaluation.
                            </p>

                            <div class="mt-5 flex items-center gap-3 rounded-2xl border border-[#DDE6F2] bg-white px-4 py-3">
                                <svg class="h-5 w-5 text-[#F97316]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6M7 4h7l5 5v11a2 2 0 01-2 2H7a2 2 0 01-2-2V6a2 2 0 012-2z"/>
                                </svg>

                                <span class="text-sm font-black text-[#111827]">
                                    Certificate of Attendance
                                </span>
                            </div>
                        </section>

                        <section class="relative rounded-2xl border border-[#DDE6F2] bg-[#F8FAFC] p-6">
                            <div class="flex h-11 w-11 items-center justify-center rounded-2xl border border-[#86EFAC] bg-[#ECFDF5] text-[#10B981]">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2.3" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75l2 2 4-5"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9 9 0 100-18 9 9 0 000 18z"/>
                                </svg>
                            </div>

                            <h3 class="mt-5 text-base font-black uppercase tracking-wide text-[#1F2937]">
                                Completed Evaluation
                            </h3>

                            <p class="mt-2 text-sm leading-6 text-[#64748B]">
                                Participant attended and successfully completed the evaluation form.
                            </p>

                            <div class="mt-5 space-y-3">
                                <div class="flex items-center gap-3 rounded-2xl border border-[#DDE6F2] bg-white px-4 py-3">
                                    <svg class="h-5 w-5 text-[#10B981]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6M7 4h7l5 5v11a2 2 0 01-2 2H7a2 2 0 01-2-2V6a2 2 0 012-2z"/>
                                    </svg>

                                    <span class="text-sm font-black text-[#111827]">
                                        Certificate of Attendance
                                    </span>
                                </div>

                                <div class="flex items-center gap-3 rounded-2xl border border-[#86EFAC] bg-[#ECFDF5] px-4 py-3">
                                    <svg class="h-5 w-5 text-[#10B981]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 9a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l-1 9 4-3 4 3-1-9"/>
                                    </svg>

                                    <span class="text-sm font-black text-[#047857]">
                                        Certificate of Participation
                                    </span>
                                </div>
                            </div>
                        </section>
                    </div>

                    <div class="mt-6 flex items-start gap-4 rounded-2xl border border-[#EAD9B6] bg-[#FFFBF5] px-5 py-4">
                        <svg class="mt-0.5 h-6 w-6 shrink-0 text-[#D2B06A]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5"/>
                        </svg>

                        <p class="text-sm font-bold leading-6 text-[#111827]">
                            Clicking confirm will send an email blast with evaluation links to
                            <span id="certificationRecipientCount" class="font-black">0</span> checked-in participants.
                        </p>
                    </div>
                </div>

                {{-- FOOTER --}}
                <div class="grid grid-cols-1 gap-4 border-t border-[#DDE6F2] bg-[#F8FAFC] px-6 py-5 md:grid-cols-[1fr_1.8fr]">
                    <button
                        type="button"
                        onclick="closeCertificationLogicModal()"
                        class="h-14 rounded-2xl border border-[#DDE6F2] bg-white text-sm font-black uppercase tracking-wide text-[#111827] transition hover:bg-[#EEF3F9]"
                    >
                        Cancel
                    </button>

                    <form id="certificationConfirmForm" action="#" method="POST">
                        @csrf
                        <button
                            type="submit"
                            class="flex h-14 w-full items-center justify-center gap-3 rounded-2xl bg-[#172233] text-sm font-black uppercase tracking-wide text-white transition hover:bg-[#0B1220]"
                        >
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 10h8M8 14h5M5 5h14v12H7l-4 4V7a2 2 0 012-2z"/>
                            </svg>
                            Confirm & Send Links
                        </button>
                    </form>
                </div>
            </div>
        </div>

            </section>
        </main>
    </div>
</div>

<script>
    const createEventModal = document.getElementById('createEventModal');
    const openCreateEventModalButton = document.getElementById('openCreateEventModal');
    const manageEventModal = document.getElementById('manageEventModal');
    const manageEventTriggers = document.querySelectorAll('[data-manage-event-trigger]');
    const manageEventHero = document.getElementById('manageEventHero');
    const manageEventImageThumb = document.getElementById('manageEventImageThumb');
    const manageEventTypeBadge = document.getElementById('manageEventTypeBadge');
    const manageEventTitle = document.getElementById('manageEventTitle');
    const manageEventDate = document.getElementById('manageEventDate');
    const manageEventLocation = document.getElementById('manageEventLocation');
    const manageEventStatusBadge = document.getElementById('manageEventStatusBadge');
    const manageSendEvaluationButton = document.getElementById('manageSendEvaluationButton');
    const openEditEventModalFromManageButton = document.getElementById('openEditEventModalFromManage');
    const certificationLogicModal = document.getElementById('certificationLogicModal');
    const certificationConfirmForm = document.getElementById('certificationConfirmForm');
    const certificationRecipientCount = document.getElementById('certificationRecipientCount');
    const editEventModal = document.getElementById('editEventModal');
    const editEventForm = document.getElementById('editEventForm');
    const editArchiveForm = document.getElementById('editArchiveForm');
    const editArchiveButton = document.getElementById('editArchiveButton');
    const editEventHeaderText = document.getElementById('editEventHeaderText');
    const editEventIdInput = document.getElementById('editEventIdInput');
    const editEventNameInput = document.getElementById('editEventNameInput');
    const editHostedByInput = document.getElementById('editHostedByInput');
    const editStartDateInput = document.getElementById('editStartDateInput');
    const editEndDateInput = document.getElementById('editEndDateInput');
    const editLocationInput = document.getElementById('editLocationInput');
    const editDescriptionInput = document.getElementById('editDescriptionInput');
    const editCurrentImagePreview = document.getElementById('editCurrentImagePreview');
    const editEventTitleLabel = document.getElementById('editEventTitleLabel');
    let currentManageEventData = null;

    function openCreateEventModal() {
        if (!createEventModal) return;
        createEventModal.classList.remove('hidden');
        createEventModal.classList.add('flex');
        document.body.classList.add('overflow-hidden');
    }

    function closeCreateEventModal() {
        if (!createEventModal) return;
        createEventModal.classList.add('hidden');
        createEventModal.classList.remove('flex');
        if (
            (!manageEventModal || manageEventModal.classList.contains('hidden')) &&
            (!certificationLogicModal || certificationLogicModal.classList.contains('hidden')) &&
            (!editEventModal || editEventModal.classList.contains('hidden'))
        ) {
            document.body.classList.remove('overflow-hidden');
        }
    }

    function openManageEventModal(eventData) {
        if (!manageEventModal) return;

        if (manageEventHero && eventData.image) {
            manageEventHero.style.backgroundImage = `linear-gradient(to right, rgba(23,34,51,0.98), rgba(23,34,51,0.85), rgba(23,34,51,0.70)), url('${eventData.image}')`;
        }
        if (manageEventImageThumb) {
            manageEventImageThumb.src = eventData.image || '';
            manageEventImageThumb.alt = eventData.title || 'Event image';
        }
        if (manageEventTypeBadge) {
            manageEventTypeBadge.textContent = eventData.type || 'Event';
        }
        if (manageEventTitle) {
            manageEventTitle.textContent = eventData.title || 'Event Title';
        }
        if (manageEventDate) {
            manageEventDate.textContent = eventData.date || 'Date TBA';
        }
        if (manageEventLocation) {
            manageEventLocation.textContent = eventData.location || 'Location TBA';
        }
        if (manageEventStatusBadge) {
            const normalizedStatus = (eventData.status || 'Active').toLowerCase();
            manageEventStatusBadge.textContent = `• ${eventData.status || 'Active'}`;
            manageEventStatusBadge.classList.toggle('bg-[#00C781]', normalizedStatus !== 'archived');
            manageEventStatusBadge.classList.toggle('bg-[#9CA3AF]', normalizedStatus === 'archived');
        }
        if (manageSendEvaluationButton) {
            const isArchived = (eventData.status || '').toLowerCase() === 'archived';
            manageSendEvaluationButton.disabled = isArchived;
            manageSendEvaluationButton.classList.toggle('opacity-50', isArchived);
            manageSendEvaluationButton.classList.toggle('cursor-not-allowed', isArchived);
            manageSendEvaluationButton.title = isArchived ? 'Archived events cannot send reminders.' : '';
        }

        manageEventModal.classList.remove('hidden');
        manageEventModal.classList.add('flex');
        document.body.classList.add('overflow-hidden');
    }

    function closeManageEventModal() {
        if (!manageEventModal) return;
        manageEventModal.classList.add('hidden');
        manageEventModal.classList.remove('flex');
        if (
            (!createEventModal || createEventModal.classList.contains('hidden')) &&
            (!certificationLogicModal || certificationLogicModal.classList.contains('hidden')) &&
            (!editEventModal || editEventModal.classList.contains('hidden'))
        ) {
            document.body.classList.remove('overflow-hidden');
        }
    }

    function openCertificationLogicModal() {
        if (!certificationLogicModal || !currentManageEventData) return;
        if (certificationConfirmForm) {
            certificationConfirmForm.action = currentManageEventData.sendEvaluationUrl || '#';
        }
        if (certificationRecipientCount) {
            const recipientCount = Number.parseInt(currentManageEventData.evaluationRecipientCount || '0', 10);
            certificationRecipientCount.textContent = Number.isNaN(recipientCount)
                ? '0'
                : recipientCount.toLocaleString();
        }
        certificationLogicModal.classList.remove('hidden');
        certificationLogicModal.classList.add('flex');
        document.body.classList.add('overflow-hidden');
    }

    function closeCertificationLogicModal() {
        if (!certificationLogicModal) return;
        certificationLogicModal.classList.add('hidden');
        certificationLogicModal.classList.remove('flex');
        if (
            (!createEventModal || createEventModal.classList.contains('hidden')) &&
            (!manageEventModal || manageEventModal.classList.contains('hidden')) &&
            (!editEventModal || editEventModal.classList.contains('hidden'))
        ) {
            document.body.classList.remove('overflow-hidden');
        }
    }

    function syncEditEventTitleLabel() {
        if (!editEventTitleLabel) return;
        const selectedEventType = document.querySelector('#editEventForm input[name="event_type"]:checked')?.value;
        editEventTitleLabel.textContent = selectedEventType === 'School Event'
            ? 'School Event Title'
            : 'Conference Event Title';
    }

    function openEditEventModal(eventData) {
        if (!editEventModal || !editEventForm) return;

        if (editEventHeaderText) {
            editEventHeaderText.textContent = `Update the details for: ${eventData.title || 'Event Title'}`;
        }
        if (editEventIdInput) editEventIdInput.value = eventData.id || '';
        if (editEventNameInput) editEventNameInput.value = eventData.title || '';
        if (editHostedByInput) editHostedByInput.value = eventData.hostedBy || '';
        if (editStartDateInput) editStartDateInput.value = eventData.startDate || '';
        if (editEndDateInput) editEndDateInput.value = eventData.endDate || '';
        if (editLocationInput) editLocationInput.value = eventData.location || '';
        if (editDescriptionInput) editDescriptionInput.value = eventData.description || '';
        if (editCurrentImagePreview) editCurrentImagePreview.src = eventData.image || '';

        editEventForm.action = eventData.updateUrl || '#';
        if (editArchiveForm) {
            editArchiveForm.action = eventData.archiveUrl || '#';
        }

        const editTypeInput = document.querySelector(`#editEventForm input[name="event_type"][value="${eventData.eventTypeValue || 'School Event'}"]`);
        if (editTypeInput) {
            editTypeInput.checked = true;
        }
        const editAttendanceInput = document.querySelector(`#editEventForm input[name="attendance_format"][value="${eventData.attendanceFormat || 'Face-to-Face'}"]`);
        if (editAttendanceInput) {
            editAttendanceInput.checked = true;
        }
        syncEditEventTitleLabel();

        closeManageEventModal();
        editEventModal.classList.remove('hidden');
        editEventModal.classList.add('flex');
        document.body.classList.add('overflow-hidden');
    }

    function closeEditEventModal() {
        if (!editEventModal) return;
        editEventModal.classList.add('hidden');
        editEventModal.classList.remove('flex');
        if (
            (!createEventModal || createEventModal.classList.contains('hidden')) &&
            (!manageEventModal || manageEventModal.classList.contains('hidden'))
        ) {
            document.body.classList.remove('overflow-hidden');
        }
    }

    if (openCreateEventModalButton) {
        openCreateEventModalButton.addEventListener('click', openCreateEventModal);
    }

    manageEventTriggers.forEach((trigger) => {
        trigger.addEventListener('click', () => {
            currentManageEventData = {
                id: trigger.dataset.eventId,
                title: trigger.dataset.eventTitle,
                type: trigger.dataset.eventType,
                date: trigger.dataset.eventDate,
                location: trigger.dataset.eventLocation,
                image: trigger.dataset.eventImage,
                status: trigger.dataset.eventStatus,
                eventTypeValue: trigger.dataset.eventTypeValue,
                hostedBy: trigger.dataset.eventHostedBy,
                attendanceFormat: trigger.dataset.eventAttendanceFormat,
                startDate: trigger.dataset.eventStartDate,
                endDate: trigger.dataset.eventEndDate,
                description: trigger.dataset.eventDescription,
                updateUrl: trigger.dataset.eventUpdateUrl,
                archiveUrl: trigger.dataset.eventArchiveUrl,
                sendEvaluationUrl: trigger.dataset.sendEvaluationUrl,
                evaluationRecipientCount: trigger.dataset.evaluationRecipientCount,
            };
            openManageEventModal(currentManageEventData);
        });
    });

    const eventTypeInputs = document.querySelectorAll('input[name="event_type"]');
    const eventTitleLabel = document.getElementById('eventTitleLabel');

    const syncEventTitleLabel = () => {
        if (!eventTitleLabel) return;
        const selectedEventType = document.querySelector('input[name="event_type"]:checked')?.value;
        eventTitleLabel.textContent = selectedEventType === 'School Event'
            ? 'School Event Title'
            : 'Conference Event Title';
    };

    eventTypeInputs.forEach((input) => {
        input.addEventListener('change', syncEventTitleLabel);
    });
    syncEventTitleLabel();

    const editEventTypeInputs = document.querySelectorAll('#editEventForm input[name="event_type"]');
    editEventTypeInputs.forEach((input) => {
        input.addEventListener('change', syncEditEventTitleLabel);
    });
    syncEditEventTitleLabel();

    if (openEditEventModalFromManageButton) {
        openEditEventModalFromManageButton.addEventListener('click', () => {
            if (currentManageEventData) {
                openEditEventModal(currentManageEventData);
            }
        });
    }

    if (manageSendEvaluationButton) {
        manageSendEvaluationButton.addEventListener('click', () => {
            if (manageSendEvaluationButton.disabled) return;
            openCertificationLogicModal();
        });
    }

    if (editArchiveButton && editArchiveForm) {
        editArchiveButton.addEventListener('click', () => {
            if (editArchiveForm.action && editArchiveForm.action !== '#') {
                editArchiveForm.submit();
            }
        });
    }

    if (createEventModal) {
        createEventModal.addEventListener('click', (event) => {
            if (event.target === createEventModal) {
                closeCreateEventModal();
            }
        });
    }

    if (manageEventModal) {
        manageEventModal.addEventListener('click', (event) => {
            if (event.target === manageEventModal) {
                closeManageEventModal();
            }
        });
    }

    if (editEventModal) {
        editEventModal.addEventListener('click', (event) => {
            if (event.target === editEventModal) {
                closeEditEventModal();
            }
        });
    }

    if (certificationLogicModal) {
        certificationLogicModal.addEventListener('click', (event) => {
            if (event.target === certificationLogicModal) {
                closeCertificationLogicModal();
            }
        });
    }

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            if (createEventModal && createEventModal.classList.contains('flex')) {
                closeCreateEventModal();
            }
            if (manageEventModal && manageEventModal.classList.contains('flex')) {
                closeManageEventModal();
            }
            if (certificationLogicModal && certificationLogicModal.classList.contains('flex')) {
                closeCertificationLogicModal();
            }
            if (editEventModal && editEventModal.classList.contains('flex')) {
                closeEditEventModal();
            }
        }
    });

    @if ($errors->any())
        openCreateEventModal();
    @endif

    @if ($errors->editEvent->any())
        openEditEventModal({
            id: '{{ old('editing_event_id', '') }}',
            title: @json(old('event_name', 'Event Title')),
            eventTypeValue: @json(old('event_type', 'School Event')),
            hostedBy: @json(old('hosted_by', '')),
            attendanceFormat: @json(old('attendance_format', 'Face-to-Face')),
            startDate: '{{ old('start_date', '') }}',
            endDate: '{{ old('end_date', '') }}',
            location: @json(old('location', '')),
            description: @json(old('description', '')),
            image: '',
            updateUrl: '{{ old('editing_event_id') ? route('admin.events.update', ['event' => old('editing_event_id')]) : '#' }}',
            archiveUrl: '{{ old('editing_event_id') ? route('admin.events.archive', ['event' => old('editing_event_id')]) : '#' }}',
        });
    @endif
</script>
@endsection
