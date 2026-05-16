@extends('layouts.app')

@section('content')
@php
    $eventCategoryList = $events
        ->map(function ($event) {
            $eventTypeRaw = strtolower((string) ($event->event_type ?? ''));
            return str_contains($eventTypeRaw, 'conference') ? 'Conference Event' : 'School Event';
        })
        ->unique()
        ->values();

    $eventMonthList = $events
        ->map(function ($event) {
            $date = $event->event_date ?? $event->start_date;
            if (! $date) {
                return null;
            }

            return [
                'value' => (int) $date->format('n'),
                'label' => $date->format('F'),
            ];
        })
        ->filter()
        ->unique('value')
        ->sortBy('value')
        ->values();

    $eventYearList = $events
        ->map(function ($event) {
            $date = $event->event_date ?? $event->start_date;
            return $date ? (int) $date->format('Y') : null;
        })
        ->filter()
        ->unique()
        ->sort()
        ->values();
    // Ensure newest-created events appear first in the grid (upper-left)
    // Robust sort: prefer `created_at`, then `event_date`/`start_date`, then `id` as fallback.
    $events = $events->sortByDesc(function ($event) {
        $ts = 0;
        if (! empty($event->created_at)) {
            if ($event->created_at instanceof \DateTimeInterface) {
                $ts = (int) $event->created_at->getTimestamp();
            } else {
                $t = strtotime((string) $event->created_at);
                $ts = $t === false ? 0 : (int) $t;
            }
        } elseif (! empty($event->event_date)) {
            if ($event->event_date instanceof \DateTimeInterface) {
                $ts = (int) $event->event_date->getTimestamp();
            } else {
                $t = strtotime((string) $event->event_date);
                $ts = $t === false ? 0 : (int) $t;
            }
        } elseif (! empty($event->start_date)) {
            if ($event->start_date instanceof \DateTimeInterface) {
                $ts = (int) $event->start_date->getTimestamp();
            } else {
                $t = strtotime((string) $event->start_date);
                $ts = $t === false ? 0 : (int) $t;
            }
        } elseif (! empty($event->event_id) || ! empty($event->id)) {
            $ts = (int) ($event->event_id ?? $event->id ?? 0);
        }

        return $ts;
    })->values();
@endphp
<div class="min-h-screen bg-gradient-to-br from-[#0F1E36] via-[#132B4A] to-[#0F1E36] font-sans text-[#F8FAFC]">
    <div class="flex">

        @include('admin.partials.sidebar')

        {{-- MAIN CONTENT --}}
        <main class="ml-[270px] min-h-screen w-full">

            @include('admin.partials.topbar', ['topbarTitle' => 'Events'])

            <section class="px-9 py-10">
        @if (session('status'))
            <div class="mb-6 rounded-2xl border border-[#60A5FA]/25 bg-[#1E375A]/65 px-5 py-4 text-sm font-semibold text-[#F8FAFC] backdrop-blur-md">
                {{ session('status') }}
            </div>
        @endif

        {{-- PAGE HEADER --}}
        <div class="flex flex-col items-start gap-6 md:flex-row md:items-start md:justify-between">
            <div>
                <div class="flex items-center gap-3">
                    <div class="h-7 w-1 rounded-full bg-[#60A5FA]"></div>
                    <h1 class="text-[28px] font-black tracking-tight text-[#F8FAFC]">
                        EVENTS MANAGEMENT
                    </h1>
                </div>

                <p class="mt-2 text-sm text-[#CBD5E1]">
                    Create, view, and organize school gatherings and conferences.
                </p>
            </div>

            <button
                type="button"
                id="openCreateEventModal"
                class="inline-flex items-center gap-3 rounded-2xl bg-[#3B82F6] px-7 py-4 text-sm font-black uppercase tracking-wide text-white shadow-sm transition hover:bg-[#2563EB]"
            >
                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                Create Event
            </button>
        </div>

        {{-- FILTER BAR --}}
        <section class="mt-12 rounded-2xl border border-[#60A5FA]/20 bg-[#1E375A]/65 p-5 shadow-lg shadow-black/20 backdrop-blur-md">
            <div class="flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between">

                {{-- Search --}}
                <div class="relative w-full xl:w-[360px]">
                    <svg class="absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-[#64748B]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m1.35-5.65a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>

                    <input
                        id="eventsSearchInput"
                        type="text"
                        placeholder="Search events..."
                        class="h-12 w-full rounded-2xl border border-[#60A5FA]/20 bg-[#0D1B31]/70 pl-12 pr-4 text-sm font-medium text-[#F8FAFC] outline-none transition placeholder:text-[#64748B] focus:border-[#60A5FA] focus:ring-2 focus:ring-[#60A5FA]/30"
                    >
                </div>

                {{-- Filters --}}
                <div class="flex flex-wrap items-center gap-3">
                    <button
                        type="button"
                        class="flex h-12 w-12 items-center justify-center rounded-2xl border border-[#60A5FA]/20 bg-[#0D1B31]/60 text-[#CBD5E1] transition hover:border-[#60A5FA]/45 hover:text-[#60A5FA]"
                        title="Filter"
                    >
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 4h18l-7 8v6l-4 2v-8L3 4z"/>
                        </svg>
                    </button>

                    <select id="eventsCategoryFilter" class="h-12 rounded-2xl border border-[#60A5FA]/20 bg-[#0D1B31]/70 px-5 text-sm font-black text-[#F8FAFC] outline-none transition focus:border-[#60A5FA] focus:ring-2 focus:ring-[#60A5FA]/30">
                        <option value="all" class="bg-[#0D1B31] text-[#F8FAFC]">All Categories</option>
                        @foreach ($eventCategoryList as $category)
                            <option value="{{ $category }}" class="bg-[#0D1B31] text-[#F8FAFC]">{{ $category }}</option>
                        @endforeach
                    </select>

                    <select id="eventsMonthFilter" class="h-12 rounded-2xl border border-[#60A5FA]/20 bg-[#0D1B31]/70 px-5 text-sm font-black text-[#F8FAFC] outline-none transition focus:border-[#60A5FA] focus:ring-2 focus:ring-[#60A5FA]/30">
                        <option value="all" class="bg-[#0D1B31] text-[#F8FAFC]">All Months</option>
                        @foreach ($eventMonthList as $month)
                            <option value="{{ $month['value'] }}" class="bg-[#0D1B31] text-[#F8FAFC]">{{ $month['label'] }}</option>
                        @endforeach
                    </select>

                    <select id="eventsYearFilter" class="h-12 rounded-2xl border border-[#60A5FA]/20 bg-[#0D1B31]/70 px-5 text-sm font-black text-[#F8FAFC] outline-none transition focus:border-[#60A5FA] focus:ring-2 focus:ring-[#60A5FA]/30">
                        <option value="all" class="bg-[#0D1B31] text-[#F8FAFC]">All Years</option>
                        @foreach ($eventYearList as $year)
                            <option value="{{ $year }}" class="bg-[#0D1B31] text-[#F8FAFC]">{{ $year }}</option>
                        @endforeach
                    </select>

                    {{-- View Toggle --}}
                    <div class="flex items-center gap-2 rounded-2xl border border-[#60A5FA]/20 bg-[#0D1B31]/70 p-1">
                        <button
                            id="gridViewButton"
                            type="button"
                            class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#3B82F6] text-white shadow-sm"
                            title="Grid View"
                        >
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 4h6v6H4V4zm10 0h6v6h-6V4zM4 14h6v6H4v-6zm10 0h6v6h-6v-6z"/>
                            </svg>
                        </button>

                        <button
                            id="listViewButton"
                            type="button"
                            class="flex h-10 w-10 items-center justify-center rounded-xl text-[#64748B] transition hover:bg-[#13284A]/70 hover:text-[#60A5FA]"
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
        <section id="eventsContainer" class="mt-7 grid grid-cols-1 gap-7 lg:grid-cols-2 2xl:grid-cols-3">
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
                    $badgeStyleClass = $isConference
                        ? 'bg-[#3B82F6] text-white shadow-sm'
                        : 'bg-[#F8FAFC]/95 text-[#0D1B31] backdrop-blur';
                    $eventStatusKey = strtolower((string) ($event->computed_status ?? $event->status ?? 'active'));
                    $eventStatusLabel = (string) ($event->computed_status_label ?? ucfirst($eventStatusKey));
                    $statusStyleClass = $eventStatusKey === 'archived'
                        ? 'bg-[#EF4444]'
                        : ($eventStatusKey === 'done' ? 'bg-[#64748B]' : 'bg-[#22C55E]');
                    $eventMonthValue = $event->event_date
                        ? $event->event_date->format('n')
                        : ($event->start_date ? $event->start_date->format('n') : '');
                    $eventYearValue = $event->event_date
                        ? $event->event_date->format('Y')
                        : ($event->start_date ? $event->start_date->format('Y') : '');
                    $eventSearch = trim(implode(' ', array_filter([
                        $event->event_name,
                        $event->location,
                        $event->hosted_by,
                        $event->attendance_format,
                        $event->description,
                        $eventTypeLabel,
                    ])));
                    $bannerImage = $event->banner_url ?: 'https://images.unsplash.com/photo-1511578314322-379afb476865?auto=format&fit=crop&w=1200&q=60';
                    $reminderSummary = $eventReminderSummary[$event->event_id] ?? null;
                    $evaluationRecipientCount = (int) ($reminderSummary['total_recipients'] ?? 0);
                    $evaluationAlreadySent = (bool) ($reminderSummary['any_sent'] ?? false);
                @endphp
                <article
                    class="group overflow-hidden rounded-2xl border border-[#60A5FA]/20 bg-[#1E375A]/65 shadow-sm transition hover:border-[#60A5FA]/40"
                    data-event-card
                    data-event-title="{{ $event->event_name }}"
                    data-event-category="{{ $eventTypeLabel }}"
                    data-event-month="{{ $eventMonthValue }}"
                    data-event-year="{{ $eventYearValue }}"
                    data-event-search="{{ $eventSearch }}"
                >

                    {{-- Image --}}
                    <div class="relative h-[190px] overflow-hidden">
                        <img
                            src="{{ $bannerImage }}"
                            alt="{{ $event->event_name }}"
                            loading="lazy"
                            decoding="async"
                            fetchpriority="low"
                            class="h-full w-full object-cover transition duration-500 group-hover:scale-105"
                        >

                        <div class="absolute inset-0 bg-gradient-to-t from-[#0D1B31] via-[#0D1B31]/30 to-transparent"></div>

                        {{-- Type Badge --}}
                        <span class="absolute left-4 top-4 rounded-xl px-4 py-2 text-xs font-black uppercase tracking-wide {{ $badgeStyleClass }}">
                            {{ $eventTypeLabel }}
                        </span>

                        {{-- Status --}}
                        <span class="absolute bottom-4 right-4 rounded-xl {{ $statusStyleClass }} px-4 py-2 text-xs font-black text-white shadow-md">
                            • {{ $eventStatusLabel }}
                        </span>
                    </div>

                    {{-- Body --}}
                    <div class="p-6">
                        <h2 class="line-clamp-1 text-xl font-black uppercase tracking-tight text-[#F8FAFC]">
                            {{ $event->event_name }}
                        </h2>

                        <div class="mt-5 space-y-2 text-sm font-medium text-[#CBD5E1]">
                            <div class="flex items-center gap-3">
                                <svg class="h-4 w-4 text-[#60A5FA]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3M4 11h16M5 5h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z"/>
                                </svg>
                                {{ $eventDate }}
                            </div>

                            <div class="flex items-center gap-3">
                                <svg class="h-4 w-4 text-[#60A5FA]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 21s7-4.438 7-11a7 7 0 10-14 0c0 6.562 7 11 7 11z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 10a2 2 0 100-4 2 2 0 000 4z"/>
                                </svg>
                                {{ $event->location ?: 'Location TBA' }}
                            </div>
                        </div>

                        <div class="mt-5 border-t border-[#1E3357] pt-5">
                            <div class="flex justify-end">
                                <button
                                    type="button"
                                    class="rounded-2xl border border-[#60A5FA]/25 bg-[#13284A]/70 px-5 py-2 text-sm font-black uppercase tracking-wide text-[#F8FAFC] transition hover:border-[#60A5FA]/50 hover:bg-[#13284A]"
                                    data-manage-event-trigger
                                    data-event-id="{{ $event->event_id }}"
                                    data-event-title="{{ $event->event_name }}"
                                    data-event-type="{{ $eventTypeLabel }}"
                                    data-event-date="{{ $eventDate }}"
                                    data-event-location="{{ $event->location ?: 'Location TBA' }}"
                                    data-event-image="{{ $bannerImage }}"
                                    data-event-status="{{ $eventStatusLabel }}"
                                    data-event-status-key="{{ $eventStatusKey }}"
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
                                    data-evaluation-already-sent="{{ $evaluationAlreadySent ? 1 : 0 }}"
                                >
                                    Manage Event ->
                                </button>
                            </div>
                        </div>
                    </div>
                </article>
            @endforeach
            <article id="eventsEmptyState" class="col-span-full rounded-2xl border border-dashed border-[#60A5FA]/25 bg-[#1E375A]/65 p-10 text-center backdrop-blur-md {{ $events->isEmpty() ? '' : 'hidden' }}">
                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl border border-[#60A5FA]/30 bg-[#0D1B31]/60">
                    <svg class="h-8 w-8 text-[#60A5FA]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3M4 11h16M5 5h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z"/>
                    </svg>
                </div>
                <h3 class="mt-5 text-xl font-black text-[#F8FAFC]">No events found</h3>
                <p id="eventsEmptyMessage" class="mt-2 text-sm font-medium text-[#94A3B8]">
                    {{ $events->isEmpty() ? 'Wala pang event records sa system.' : 'No events found.' }}
                </p>
            </article>
        </section>

        {{-- CREATE EVENT MODAL --}}
        <div
            id="createEventModal"
            class="fixed inset-0 z-50 hidden items-center justify-center bg-black/70 px-4 py-6 backdrop-blur-sm"
        >
            <div class="relative flex max-h-[92vh] w-full max-w-[760px] flex-col overflow-hidden rounded-3xl border border-[#60A5FA]/25 bg-[#10213A] shadow-2xl shadow-black/40">

                {{-- Modal Header --}}
                <div class="flex items-start justify-between border-b border-[#1E3357] bg-[#0D1B31] px-6 py-6 text-white">
                    <div>
                        <h2 class="text-2xl font-black uppercase tracking-wide text-[#F8FAFC]">
                            Create New Event
                        </h2>
                        <p class="mt-2 text-sm text-[#60A5FA]">
                            Fill in the details for the upcoming event.
                        </p>
                    </div>

                    <button
                        type="button"
                        onclick="closeCreateEventModal()"
                        class="flex h-10 w-10 items-center justify-center rounded-full border border-[#60A5FA]/20 bg-[#13284A]/70 text-[#F8FAFC] transition hover:border-[#60A5FA]/50 hover:bg-[#13284A]"
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
                    class="overflow-y-auto bg-[#10213A] px-6 py-6"
                >
                    @csrf

                    {{-- BASIC INFO --}}
                    <section class="rounded-2xl border border-[#60A5FA]/20 bg-[#13284A]/60 p-6">
                        <h3 class="text-base font-black uppercase tracking-wide text-[#F8FAFC]">
                            Basic Info
                        </h3>

                        <div class="mt-4 border-t border-[#1E3357] pt-5">

                            {{-- Event Type --}}
                            <label class="mb-3 block text-sm font-black uppercase tracking-widest text-[#94A3B8]">
                                Event Type
                            </label>

                            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                <label class="relative block cursor-pointer">
                                    <input type="radio" name="event_type" value="School Event" class="peer sr-only" @checked(old('event_type', 'Conference') === 'School Event')>
                                    <span class="pointer-events-none absolute right-3 top-3 z-10 flex h-5 w-5 items-center justify-center rounded-full border border-[#60A5FA]/30 bg-[#0D1B31] text-[#0D1B31] transition peer-checked:border-transparent peer-checked:bg-[#3B82F6] peer-checked:text-white">
                                        <svg class="h-3 w-3" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                    </span>
                                    <div class="rounded-2xl border border-[#60A5FA]/20 bg-[#0D1B31]/70 px-5 py-5 text-center transition hover:border-[#60A5FA]/40 peer-checked:border-[#60A5FA] peer-checked:bg-[#60A5FA]/10">
                                        <p class="text-base font-black uppercase tracking-wide text-[#F8FAFC]">
                                            School Event
                                        </p>
                                        <p class="mt-2 text-sm text-[#94A3B8]">
                                            Campus activities
                                        </p>
                                    </div>
                                </label>

                                <label class="relative block cursor-pointer">
                                    <input type="radio" name="event_type" value="Conference" class="peer sr-only" @checked(old('event_type', 'Conference') === 'Conference')>
                                    <span class="pointer-events-none absolute right-3 top-3 z-10 flex h-5 w-5 items-center justify-center rounded-full border border-[#60A5FA]/30 bg-[#0D1B31] text-[#0D1B31] transition peer-checked:border-transparent peer-checked:bg-[#3B82F6] peer-checked:text-white">
                                        <svg class="h-3 w-3" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                    </span>
                                    <div class="rounded-2xl border border-[#60A5FA]/20 bg-[#0D1B31]/70 px-5 py-5 text-center transition hover:border-[#60A5FA]/40 peer-checked:border-[#60A5FA] peer-checked:bg-[#60A5FA]/10">
                                        <p class="text-base font-black uppercase tracking-wide text-[#F8FAFC]">
                                            Conference Event
                                        </p>
                                        <p class="mt-2 text-sm text-[#94A3B8]">
                                            Requires PDF paper
                                        </p>
                                    </div>
                                </label>
                            </div>
                            @error('event_type') <p class="mt-2 text-xs font-semibold text-[#EF4444]">{{ $message }}</p> @enderror

                            {{-- Event Title --}}
                            <div class="mt-5">
                                <label id="eventTitleLabel" class="mb-3 block text-sm font-black uppercase tracking-widest text-[#94A3B8]">
                                    {{ old('event_type', 'Conference') === 'School Event' ? 'School Event Title' : 'Conference Event Title' }}
                                </label>

                                <input
                                    type="text"
                                    name="event_name"
                                    value="{{ old('event_name') }}"
                                    placeholder="e.g. Tech Innovations Summit"
                                    class="h-14 w-full rounded-2xl border border-[#60A5FA]/20 bg-[#0D1B31]/70 px-5 text-base text-[#F8FAFC] outline-none transition placeholder:text-[#64748B] focus:border-[#60A5FA] focus:ring-2 focus:ring-[#60A5FA]/30"
                                >
                                @error('event_name') <p class="mt-2 text-xs font-semibold text-[#EF4444]">{{ $message }}</p> @enderror
                            </div>

                            {{-- Hosted By --}}
                            <div class="mt-5">
                                <label class="mb-3 block text-sm font-black uppercase tracking-widest text-[#94A3B8]">
                                    Hosted By: (Department / School / Program)
                                </label>

                                <input
                                    type="text"
                                    name="hosted_by"
                                    value="{{ old('hosted_by') }}"
                                    placeholder="e.g. College of Computer Studies"
                                    class="h-14 w-full rounded-2xl border border-[#60A5FA]/20 bg-[#0D1B31]/70 px-5 text-base text-[#F8FAFC] outline-none transition placeholder:text-[#64748B] focus:border-[#60A5FA] focus:ring-2 focus:ring-[#60A5FA]/30"
                                >
                                @error('hosted_by') <p class="mt-2 text-xs font-semibold text-[#EF4444]">{{ $message }}</p> @enderror
                            </div>

                            {{-- Attendance Format --}}
                            <div class="mt-5">
                                <label class="mb-3 block text-sm font-black uppercase tracking-widest text-[#94A3B8]">
                                    Attendance Format
                                </label>

                                <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                                    <label class="block cursor-pointer">
                                        <input type="radio" name="attendance_format" value="Online" class="peer sr-only" @checked(old('attendance_format', 'Hybrid') === 'Online')>
                                        <div class="rounded-xl border border-[#60A5FA]/20 bg-[#0D1B31]/70 px-5 py-4 text-center text-sm font-black uppercase tracking-wide text-[#CBD5E1] transition hover:border-[#60A5FA]/40 peer-checked:border-[#60A5FA] peer-checked:bg-[#60A5FA]/10 peer-checked:text-[#60A5FA]">
                                            Online
                                        </div>
                                    </label>

                                    <label class="block cursor-pointer">
                                        <input type="radio" name="attendance_format" value="Face-to-Face" class="peer sr-only" @checked(old('attendance_format', 'Hybrid') === 'Face-to-Face')>
                                        <div class="rounded-xl border border-[#60A5FA]/20 bg-[#0D1B31]/70 px-5 py-4 text-center text-sm font-black uppercase tracking-wide text-[#CBD5E1] transition hover:border-[#60A5FA]/40 peer-checked:border-[#60A5FA] peer-checked:bg-[#60A5FA]/10 peer-checked:text-[#60A5FA]">
                                            Face-to-Face
                                        </div>
                                    </label>

                                    <label class="block cursor-pointer">
                                        <input type="radio" name="attendance_format" value="Hybrid" class="peer sr-only" @checked(old('attendance_format', 'Hybrid') === 'Hybrid')>
                                        <div class="rounded-xl border border-[#60A5FA]/20 bg-[#0D1B31]/70 px-5 py-4 text-center text-sm font-black uppercase tracking-wide text-[#CBD5E1] transition hover:border-[#60A5FA]/40 peer-checked:border-[#60A5FA] peer-checked:bg-[#60A5FA]/10 peer-checked:text-[#60A5FA]">
                                            Hybrid
                                        </div>
                                    </label>
                                </div>
                                @error('attendance_format') <p class="mt-2 text-xs font-semibold text-[#EF4444]">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </section>

                    {{-- SCHEDULE & LOCATION --}}
                    <section class="mt-6 rounded-2xl border border-[#60A5FA]/20 bg-[#13284A]/60 p-6">
                        <h3 class="text-base font-black uppercase tracking-wide text-[#F8FAFC]">
                            Schedule & Location
                        </h3>

                        <div class="mt-4 border-t border-[#1E3357] pt-5">
                            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">

                                <div>
                                    <label class="mb-3 flex items-center gap-1 text-sm font-black uppercase tracking-widest text-[#94A3B8]">
                                        <span class="text-[#60A5FA]">□</span>
                                        Start Date
                                    </label>

                                    <input
                                        type="date"
                                        name="start_date"
                                        value="{{ old('start_date') }}"
                                        class="h-14 w-full rounded-2xl border border-[#60A5FA]/20 bg-[#0D1B31]/70 px-5 text-base text-[#F8FAFC] outline-none transition [color-scheme:dark] focus:border-[#60A5FA] focus:ring-2 focus:ring-[#60A5FA]/30"
                                    >
                                    @error('start_date') <p class="mt-2 text-xs font-semibold text-[#EF4444]">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label class="mb-3 flex items-center gap-1 text-sm font-black uppercase tracking-widest text-[#94A3B8]">
                                        <span class="text-[#60A5FA]">□</span>
                                        End Date
                                    </label>

                                    <input
                                        type="date"
                                        name="end_date"
                                        value="{{ old('end_date') }}"
                                        class="h-14 w-full rounded-2xl border border-[#60A5FA]/20 bg-[#0D1B31]/70 px-5 text-base text-[#F8FAFC] outline-none transition [color-scheme:dark] focus:border-[#60A5FA] focus:ring-2 focus:ring-[#60A5FA]/30"
                                    >
                                    @error('end_date') <p class="mt-2 text-xs font-semibold text-[#EF4444]">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label class="mb-3 flex items-center gap-1 text-sm font-black uppercase tracking-widest text-[#94A3B8]">
                                        <span class="text-[#60A5FA]">⌖</span>
                                        Location
                                    </label>

                                    <input
                                        type="text"
                                        name="location"
                                        value="{{ old('location') }}"
                                        placeholder="e.g. Main Auditorium"
                                        class="h-14 w-full rounded-2xl border border-[#60A5FA]/20 bg-[#0D1B31]/70 px-5 text-base text-[#F8FAFC] outline-none transition placeholder:text-[#64748B] focus:border-[#60A5FA] focus:ring-2 focus:ring-[#60A5FA]/30"
                                    >
                                    @error('location') <p class="mt-2 text-xs font-semibold text-[#EF4444]">{{ $message }}</p> @enderror
                                </div>

                            </div>
                        </div>
                    </section>

                    {{-- CONTENT & MEDIA --}}
                    <section class="mt-6 rounded-2xl border border-[#60A5FA]/20 bg-[#13284A]/60 p-6">
                        <h3 class="text-base font-black uppercase tracking-wide text-[#F8FAFC]">
                            Content & Media
                        </h3>

                        <div class="mt-4 border-t border-[#1E3357] pt-5">

                            {{-- Description --}}
                            <div>
                                <label class="mb-3 flex items-center gap-2 text-sm font-black uppercase tracking-widest text-[#94A3B8]">
                                    <span class="text-[#60A5FA]">☰</span>
                                    Full Description
                                </label>

                                <textarea
                                    name="description"
                                    rows="4"
                                    placeholder="Write detailed information..."
                                    class="w-full resize-none rounded-2xl border border-[#60A5FA]/20 bg-[#0D1B31]/70 px-5 py-4 text-base text-[#F8FAFC] outline-none transition placeholder:text-[#64748B] focus:border-[#60A5FA] focus:ring-2 focus:ring-[#60A5FA]/30"
                                >{{ old('description') }}</textarea>
                                @error('description') <p class="mt-2 text-xs font-semibold text-[#EF4444]">{{ $message }}</p> @enderror
                            </div>

                            {{-- Banner Upload --}}
                            <div class="mt-6">
                                <label class="mb-3 flex items-center gap-2 text-sm font-black uppercase tracking-widest text-[#94A3B8]">
                                    <span class="text-[#60A5FA]">▧</span>
                                    Event Banner Image
                                </label>

                                <label id="createBannerUploadBox" class="relative flex min-h-[180px] cursor-pointer flex-col items-center justify-center overflow-hidden rounded-2xl border-2 border-dashed border-[#60A5FA]/35 bg-[#0D1B31]/70 px-6 py-8 text-center transition hover:border-[#60A5FA]/60 hover:bg-[#60A5FA]/10">
                                    <input
                                        type="file"
                                        id="createBannerImageInput"
                                        name="banner_image"
                                        accept="image/png,image/jpeg,image/jpg,image/webp"
                                        class="hidden"
                                    >

                                    <img
                                        id="createBannerImagePreview"
                                        src=""
                                        alt="Selected event banner preview"
                                        class="absolute inset-0 hidden h-full w-full object-cover opacity-30"
                                    >

                                    <svg id="createBannerUploadIcon" class="relative z-10 h-12 w-12 text-[#60A5FA]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01.88-7.903A5 5 0 1117.9 9H18a4 4 0 010 8h-1m-5-4v8m0 0l-3-3m3 3l3-3"/>
                                    </svg>

                                    <p id="createBannerUploadTitle" class="relative z-10 mt-3 text-base font-black text-[#F8FAFC]">
                                        Upload image
                                    </p>

                                    <p id="createBannerUploadHint" class="relative z-10 mt-2 text-sm text-[#94A3B8]">
                                        PNG, JPG, WEBP up to 5MB
                                    </p>

                                    <p id="createBannerFileName" class="relative z-10 mt-2 hidden max-w-full truncate text-xs font-bold text-[#F8FAFC]"></p>
                                </label>
                                @error('banner_image') <p class="mt-2 text-xs font-semibold text-[#EF4444]">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </section>

                    {{-- ACTION BUTTONS --}}
                    <div class="sticky bottom-0 mt-6 grid grid-cols-1 gap-4 bg-[#10213A] pt-4 md:grid-cols-[220px_1fr]">
                        <button
                            type="button"
                            onclick="closeCreateEventModal()"
                            class="h-14 rounded-2xl border border-[#60A5FA]/25 bg-[#13284A]/70 text-sm font-black uppercase tracking-wide text-[#F8FAFC] transition hover:border-[#60A5FA]/50 hover:bg-[#13284A]"
                        >
                            Cancel
                        </button>

                        <button
                            type="submit"
                            class="h-14 rounded-2xl bg-[#3B82F6] text-sm font-black uppercase tracking-wide text-white shadow-sm transition hover:bg-[#2563EB]"
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
            class="fixed inset-0 z-50 hidden items-center justify-center bg-black/70 px-4 py-6 backdrop-blur-sm"
        >
            <div class="relative flex max-h-[92vh] w-full max-w-[760px] flex-col overflow-hidden rounded-3xl border border-[#60A5FA]/25 bg-[#10213A] shadow-2xl shadow-black/40">

                {{-- MODAL HEADER --}}
                <div class="flex items-center justify-between border-b border-[#1E3357] bg-[#0D1B31] px-7 py-6 text-white">
                    <div class="flex items-center gap-3">
                        <svg class="h-6 w-6 text-[#60A5FA]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3M4 11h16M5 5h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z"/>
                        </svg>

                        <h2 class="text-2xl font-black uppercase tracking-wide text-[#F8FAFC]">
                            Manage Event
                        </h2>
                    </div>

                    <button
                        type="button"
                        onclick="closeManageEventModal()"
                        class="flex h-10 w-10 items-center justify-center rounded-full border border-[#60A5FA]/20 bg-[#13284A]/70 text-[#F8FAFC] transition hover:border-[#60A5FA]/50 hover:bg-[#13284A]"
                    >
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2.3" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                {{-- MODAL BODY --}}
                <div class="overflow-y-auto bg-[#10213A] px-7 py-7">

                    {{-- EVENT HERO --}}
                    <div
                        id="manageEventHero"
                        class="relative overflow-hidden rounded-2xl border border-[#60A5FA]/25 bg-[#0D1B31] p-6 text-white shadow-lg shadow-black/30"
                        style="background-size: cover; background-position: center;"
                    >
                        <div class="flex items-center justify-between gap-5">
                            <div class="flex items-center gap-5">
                                <img
                                    id="manageEventImageThumb"
                                    src=""
                                    alt="Event image"
                                    class="h-20 w-20 rounded-2xl border border-[#60A5FA]/30 object-cover"
                                >

                                <div>
                                    <span id="manageEventTypeBadge" class="inline-flex rounded-xl border border-[#60A5FA]/30 bg-[#0D1B31]/70 px-4 py-2 text-xs font-black uppercase tracking-wide text-[#60A5FA]">
                                        School Event
                                    </span>

                                    <h3 id="manageEventTitle" class="mt-3 text-xl font-black uppercase tracking-wide text-[#F8FAFC]">
                                        Event Title
                                    </h3>

                                    <div class="mt-2 flex flex-wrap items-center gap-4 text-sm text-[#CBD5E1]">
                                        <div class="flex items-center gap-2">
                                            <svg class="h-4 w-4 text-[#60A5FA]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3M4 11h16M5 5h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z"/>
                                            </svg>
                                            <span id="manageEventDate">Date TBA</span>
                                        </div>

                                        <div class="flex items-center gap-2">
                                            <svg class="h-4 w-4 text-[#60A5FA]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 21s7-4.438 7-11a7 7 0 10-14 0c0 6.562 7 11 7 11z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 10a2 2 0 100-4 2 2 0 000 4z"/>
                                            </svg>
                                            <span id="manageEventLocation">Location TBA</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <span id="manageEventStatusBadge" class="shrink-0 rounded-xl bg-[#22C55E] px-5 py-3 text-sm font-black text-white shadow-md">
                                • Active
                            </span>
                        </div>
                    </div>

                    {{-- EDIT DETAILS BUTTON --}}
                    <div class="mt-7 flex justify-end">
                        <button
                            id="openEditEventModalFromManage"
                            type="button"
                            class="flex items-center gap-3 rounded-2xl border border-[#60A5FA]/25 bg-[#13284A]/70 px-7 py-3 text-sm font-black uppercase tracking-wide text-[#F8FAFC] transition hover:border-[#60A5FA]/50 hover:bg-[#13284A]"
                        >
                            <svg class="h-5 w-5 text-[#60A5FA]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 7h16M4 12h10M4 17h7"/>
                            </svg>
                            Edit Details
                        </button>
                    </div>

                    {{-- POST-EVENT CONTROLS --}}
                    <section id="managePostEventSection" class="mt-7 overflow-hidden rounded-2xl border border-[#60A5FA]/20 bg-[#13284A]/60">
                        <div class="border-b border-[#1E3357] bg-[#0D1B31]/70 px-7 py-6">
                            <div class="flex items-start gap-3">
                                <svg class="mt-1 h-5 w-5 text-[#60A5FA]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15l-3.5 2 1-4L6 10h4l2-4 2 4h4l-3.5 3 1 4L12 15z"/>
                                </svg>

                                <div>
                                    <h3 class="text-xl font-black uppercase tracking-wide text-[#F8FAFC]">
                                        Post-Event Controls
                                    </h3>
                                    <p class="mt-1 text-sm text-[#CBD5E1]">
                                        Manage post-event actions like evaluations and certificates.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col items-center justify-center px-8 py-10 text-center">
                            <svg class="h-16 w-16 text-[#60A5FA]" fill="none" stroke="currentColor" stroke-width="2.3" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 9a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l-1 9 4-3 4 3-1-9"/>
                            </svg>

                            <h4 class="mt-5 text-xl font-black uppercase tracking-wide text-[#F8FAFC]">
                                Event Concluded?
                            </h4>

                            <p class="mt-3 max-w-[520px] text-sm leading-6 text-[#CBD5E1]">
                                Initiate the evaluation process and generate certificates automatically based on attendance and feedback completion.
                            </p>

                            <button
                                id="manageSendEvaluationButton"
                                type="button"
                                class="mt-7 flex items-center gap-3 rounded-2xl bg-[#3B82F6] px-10 py-4 text-sm font-black uppercase tracking-wide text-white shadow-sm transition hover:bg-[#2563EB]"
                            >
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 10h8M8 14h5M5 5h14v12H7l-4 4V7a2 2 0 012-2z"/>
                                </svg>
                                Send Evaluation Links
                            </button>

                            <p
                                id="manageEvaluationStatusMessage"
                                class="mt-6 hidden rounded-2xl border border-[#FACC15]/30 bg-[#FACC15]/10 px-5 py-3 text-sm font-bold text-[#FACC15]"
                            >
                                Evaluation links already sent.
                            </p>
                        </div>
                    </section>

                </div>

                {{-- MODAL FOOTER --}}
                <div class="flex justify-end border-t border-[#1E3357] bg-[#0D1B31] px-7 py-5">
                    <button
                        type="button"
                        onclick="closeManageEventModal()"
                        class="rounded-2xl border border-[#60A5FA]/25 bg-[#13284A]/70 px-8 py-3 text-sm font-black uppercase tracking-wide text-[#F8FAFC] transition hover:border-[#60A5FA]/50 hover:bg-[#13284A]"
                    >
                        Close
                    </button>
                </div>
            </div>
        </div>

        {{-- EDIT EVENT MODAL --}}
        <div
            id="editEventModal"
            class="fixed inset-0 z-[60] hidden items-center justify-center bg-black/70 px-4 py-6 backdrop-blur-sm"
        >
            <div class="relative flex max-h-[92vh] w-full max-w-[760px] flex-col overflow-hidden rounded-3xl border border-[#60A5FA]/25 bg-[#10213A] shadow-2xl shadow-black/40">

                {{-- MODAL HEADER --}}
                <div class="flex items-start justify-between border-b border-[#1E3357] bg-[#0D1B31] px-7 py-6 text-white">
                    <div>
                        <h2 class="text-2xl font-black uppercase tracking-wide text-[#F8FAFC]">
                            Edit Event
                        </h2>
                        <p id="editEventHeaderText" class="mt-2 text-sm text-[#60A5FA]">
                            Update the details for: Event Title
                        </p>
                    </div>

                    <button
                        type="button"
                        onclick="closeEditEventModal()"
                        class="flex h-10 w-10 items-center justify-center rounded-full border border-[#60A5FA]/20 bg-[#13284A]/70 text-[#F8FAFC] transition hover:border-[#60A5FA]/50 hover:bg-[#13284A]"
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
                    class="overflow-y-auto bg-[#10213A] px-7 py-7"
                >
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="editEventIdInput" name="editing_event_id" value="">

                    {{-- BASIC INFO --}}
                    <section class="rounded-2xl border border-[#60A5FA]/20 bg-[#13284A]/60 p-6">
                        <h3 class="text-base font-black uppercase tracking-wide text-[#F8FAFC]">
                            Basic Info
                        </h3>

                        <div class="mt-4 border-t border-[#1E3357] pt-5">
                            <label class="mb-3 block text-sm font-black uppercase tracking-widest text-[#94A3B8]">
                                Event Type
                            </label>

                            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                <label class="relative block cursor-pointer">
                                    <input type="radio" name="event_type" value="School Event" class="peer sr-only">
                                    <span class="pointer-events-none absolute right-3 top-3 z-10 flex h-5 w-5 items-center justify-center rounded-full border border-[#60A5FA]/30 bg-[#0D1B31] text-[#0D1B31] transition peer-checked:border-transparent peer-checked:bg-[#3B82F6] peer-checked:text-white">
                                        <svg class="h-3 w-3" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                    </span>
                                    <div class="rounded-2xl border border-[#60A5FA]/20 bg-[#0D1B31]/70 px-5 py-5 text-center transition hover:border-[#60A5FA]/40 peer-checked:border-[#60A5FA] peer-checked:bg-[#60A5FA]/10">
                                        <p class="text-base font-black uppercase tracking-wide text-[#F8FAFC]">
                                            School Event
                                        </p>
                                        <p class="mt-2 text-sm text-[#94A3B8]">
                                            Campus activities
                                        </p>
                                    </div>
                                </label>

                                <label class="relative block cursor-pointer">
                                    <input type="radio" name="event_type" value="Conference" class="peer sr-only">
                                    <span class="pointer-events-none absolute right-3 top-3 z-10 flex h-5 w-5 items-center justify-center rounded-full border border-[#60A5FA]/30 bg-[#0D1B31] text-[#0D1B31] transition peer-checked:border-transparent peer-checked:bg-[#3B82F6] peer-checked:text-white">
                                        <svg class="h-3 w-3" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                    </span>
                                    <div class="rounded-2xl border border-[#60A5FA]/20 bg-[#0D1B31]/70 px-5 py-5 text-center transition hover:border-[#60A5FA]/40 peer-checked:border-[#60A5FA] peer-checked:bg-[#60A5FA]/10">
                                        <p class="text-base font-black uppercase tracking-wide text-[#F8FAFC]">
                                            Conference Event
                                        </p>
                                        <p class="mt-2 text-sm text-[#94A3B8]">
                                            Requires PDF paper
                                        </p>
                                    </div>
                                </label>
                            </div>
                            @error('event_type', 'editEvent') <p class="mt-2 text-xs font-semibold text-[#EF4444]">{{ $message }}</p> @enderror

                            <div class="mt-5">
                                <label id="editEventTitleLabel" class="mb-3 block text-sm font-black uppercase tracking-widest text-[#94A3B8]">
                                    Event Title
                                </label>

                                <input
                                    type="text"
                                    id="editEventNameInput"
                                    name="event_name"
                                    value="{{ old('event_name') }}"
                                    class="h-14 w-full rounded-2xl border border-[#60A5FA]/20 bg-[#0D1B31]/70 px-5 text-base text-[#F8FAFC] outline-none transition focus:border-[#60A5FA] focus:ring-2 focus:ring-[#60A5FA]/30"
                                >
                                @error('event_name', 'editEvent') <p class="mt-2 text-xs font-semibold text-[#EF4444]">{{ $message }}</p> @enderror
                            </div>

                            <div class="mt-5">
                                <label class="mb-3 block text-sm font-black uppercase tracking-widest text-[#94A3B8]">
                                    Hosted By: (Department / School / Program)
                                </label>

                                <input
                                    type="text"
                                    id="editHostedByInput"
                                    name="hosted_by"
                                    value="{{ old('hosted_by') }}"
                                    class="h-14 w-full rounded-2xl border border-[#60A5FA]/20 bg-[#0D1B31]/70 px-5 text-base text-[#F8FAFC] outline-none transition focus:border-[#60A5FA] focus:ring-2 focus:ring-[#60A5FA]/30"
                                >
                                @error('hosted_by', 'editEvent') <p class="mt-2 text-xs font-semibold text-[#EF4444]">{{ $message }}</p> @enderror
                            </div>

                            <div class="mt-5">
                                <label class="mb-3 block text-sm font-black uppercase tracking-widest text-[#94A3B8]">
                                    Attendance Format
                                </label>

                                <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                                    <label class="block cursor-pointer">
                                        <input type="radio" name="attendance_format" value="Online" class="peer sr-only">
                                        <div class="rounded-xl border border-[#60A5FA]/20 bg-[#0D1B31]/70 px-5 py-4 text-center text-sm font-black uppercase tracking-wide text-[#CBD5E1] transition hover:border-[#60A5FA]/40 peer-checked:border-[#60A5FA] peer-checked:bg-[#60A5FA]/10 peer-checked:text-[#60A5FA]">
                                            Online
                                        </div>
                                    </label>

                                    <label class="block cursor-pointer">
                                        <input type="radio" name="attendance_format" value="Face-to-Face" class="peer sr-only">
                                        <div class="rounded-xl border border-[#60A5FA]/20 bg-[#0D1B31]/70 px-5 py-4 text-center text-sm font-black uppercase tracking-wide text-[#CBD5E1] transition hover:border-[#60A5FA]/40 peer-checked:border-[#60A5FA] peer-checked:bg-[#60A5FA]/10 peer-checked:text-[#60A5FA]">
                                            Face-to-Face
                                        </div>
                                    </label>

                                    <label class="block cursor-pointer">
                                        <input type="radio" name="attendance_format" value="Hybrid" class="peer sr-only">
                                        <div class="rounded-xl border border-[#60A5FA]/20 bg-[#0D1B31]/70 px-5 py-4 text-center text-sm font-black uppercase tracking-wide text-[#CBD5E1] transition hover:border-[#60A5FA]/40 peer-checked:border-[#60A5FA] peer-checked:bg-[#60A5FA]/10 peer-checked:text-[#60A5FA]">
                                            Hybrid
                                        </div>
                                    </label>
                                </div>
                                @error('attendance_format', 'editEvent') <p class="mt-2 text-xs font-semibold text-[#EF4444]">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </section>

                    {{-- SCHEDULE & LOCATION --}}
                    <section class="mt-6 rounded-2xl border border-[#60A5FA]/20 bg-[#13284A]/60 p-6">
                        <h3 class="text-base font-black uppercase tracking-wide text-[#F8FAFC]">
                            Schedule & Location
                        </h3>

                        <div class="mt-4 border-t border-[#1E3357] pt-5">
                            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                                <div>
                                    <label class="mb-3 flex items-center gap-1 text-sm font-black uppercase tracking-widest text-[#94A3B8]">
                                        <svg class="h-4 w-4 text-[#60A5FA]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3M4 11h16M5 5h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z"/>
                                        </svg>
                                        Start Date
                                    </label>

                                    <input
                                        type="date"
                                        id="editStartDateInput"
                                        name="start_date"
                                        value="{{ old('start_date') }}"
                                        class="h-14 w-full rounded-2xl border border-[#60A5FA]/20 bg-[#0D1B31]/70 px-5 text-base text-[#F8FAFC] outline-none transition [color-scheme:dark] focus:border-[#60A5FA] focus:ring-2 focus:ring-[#60A5FA]/30"
                                    >
                                    @error('start_date', 'editEvent') <p class="mt-2 text-xs font-semibold text-[#EF4444]">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label class="mb-3 flex items-center gap-1 text-sm font-black uppercase tracking-widest text-[#94A3B8]">
                                        <svg class="h-4 w-4 text-[#60A5FA]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3M4 11h16M5 5h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z"/>
                                        </svg>
                                        End Date
                                    </label>

                                    <input
                                        type="date"
                                        id="editEndDateInput"
                                        name="end_date"
                                        value="{{ old('end_date') }}"
                                        class="h-14 w-full rounded-2xl border border-[#60A5FA]/20 bg-[#0D1B31]/70 px-5 text-base text-[#F8FAFC] outline-none transition [color-scheme:dark] focus:border-[#60A5FA] focus:ring-2 focus:ring-[#60A5FA]/30"
                                    >
                                    @error('end_date', 'editEvent') <p class="mt-2 text-xs font-semibold text-[#EF4444]">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label class="mb-3 flex items-center gap-1 text-sm font-black uppercase tracking-widest text-[#94A3B8]">
                                        <svg class="h-4 w-4 text-[#60A5FA]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
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
                                        class="h-14 w-full rounded-2xl border border-[#60A5FA]/20 bg-[#0D1B31]/70 px-5 text-base text-[#F8FAFC] outline-none transition focus:border-[#60A5FA] focus:ring-2 focus:ring-[#60A5FA]/30"
                                    >
                                    @error('location', 'editEvent') <p class="mt-2 text-xs font-semibold text-[#EF4444]">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- CONTENT & MEDIA --}}
                    <section class="mt-6 rounded-2xl border border-[#60A5FA]/20 bg-[#13284A]/60 p-6">
                        <h3 class="text-base font-black uppercase tracking-wide text-[#F8FAFC]">
                            Content & Media
                        </h3>

                        <div class="mt-4 border-t border-[#1E3357] pt-5">
                            <div>
                                <label class="mb-3 flex items-center gap-2 text-sm font-black uppercase tracking-widest text-[#94A3B8]">
                                    <svg class="h-4 w-4 text-[#60A5FA]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 7h16M4 12h10M4 17h7"/>
                                    </svg>
                                    Description
                                </label>

                                <textarea
                                    id="editDescriptionInput"
                                    name="description"
                                    rows="4"
                                    class="w-full resize-none rounded-2xl border border-[#60A5FA]/20 bg-[#0D1B31]/70 px-5 py-4 text-base leading-7 text-[#F8FAFC] outline-none transition focus:border-[#60A5FA] focus:ring-2 focus:ring-[#60A5FA]/30"
                                >{{ old('description') }}</textarea>
                                @error('description', 'editEvent') <p class="mt-2 text-xs font-semibold text-[#EF4444]">{{ $message }}</p> @enderror
                            </div>

                            <div class="mt-6">
                                <label id="editBannerUploadBox" class="relative flex min-h-[130px] cursor-pointer flex-col items-center justify-center overflow-hidden rounded-2xl border-2 border-dashed border-[#60A5FA]/35 bg-[#0D1B31]/70 px-6 py-8 text-center transition hover:border-[#60A5FA]/60 hover:bg-[#60A5FA]/10">
                                    <input
                                        type="file"
                                        id="editBannerImageInput"
                                        name="banner_image"
                                        accept="image/png,image/jpeg,image/jpg,image/webp"
                                        class="hidden"
                                    >

                                    <img
                                        id="editCurrentImagePreview"
                                        src=""
                                        alt="Current Event Banner"
                                        class="absolute inset-0 h-full w-full object-cover opacity-25"
                                    >

                                    <div class="relative z-10 flex flex-col items-center">
                                        <svg id="editBannerUploadIcon" class="h-12 w-12 text-[#60A5FA]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01.88-7.903A5 5 0 1117.9 9H18a4 4 0 010 8h-1m-5-4v8m0 0l-3-3m3 3l3-3"/>
                                        </svg>

                                        <p id="editBannerUploadTitle" class="mt-3 text-base font-black text-[#F8FAFC]">
                                            Replace image
                                        </p>

                                        <p id="editBannerUploadHint" class="mt-2 text-sm text-[#94A3B8]">
                                            PNG, JPG, WEBP up to 5MB
                                        </p>

                                        <p id="editBannerFileName" class="mt-2 hidden max-w-full truncate text-xs font-bold text-[#F8FAFC]"></p>
                                    </div>
                                </label>
                                @error('banner_image', 'editEvent') <p class="mt-2 text-xs font-semibold text-[#EF4444]">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </section>

                    {{-- ACTION BUTTONS --}}
                    <div class="sticky bottom-0 mt-7 grid grid-cols-1 gap-4 bg-[#10213A] pt-4 md:grid-cols-[180px_180px_1fr]">
                        <button
                            type="button"
                            onclick="closeEditEventModal()"
                            class="h-14 rounded-2xl border border-[#60A5FA]/25 bg-[#13284A]/70 text-sm font-black uppercase tracking-wide text-[#F8FAFC] transition hover:border-[#60A5FA]/50 hover:bg-[#13284A]"
                        >
                            Cancel
                        </button>

                        <button
                            id="editArchiveButton"
                            type="button"
                            class="flex h-14 items-center justify-center gap-2 rounded-2xl border border-[#EF4444]/40 bg-[#EF4444]/10 text-sm font-black uppercase tracking-wide text-[#FCA5A5] transition hover:border-[#EF4444] hover:bg-[#EF4444]/20"
                        >
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 8h14M8 8V6a2 2 0 012-2h4a2 2 0 012 2v2m1 0v12a2 2 0 01-2 2H7a2 2 0 01-2-2V8h14z"/>
                            </svg>
                            Archive
                        </button>

                        <button
                            type="submit"
                            class="h-14 rounded-2xl bg-[#3B82F6] text-sm font-black uppercase tracking-wide text-white shadow-sm transition hover:bg-[#2563EB]"
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
            class="fixed inset-0 z-[70] hidden items-center justify-center bg-black/75 px-4 py-6 backdrop-blur-sm"
        >
            <div class="relative flex max-h-[92vh] w-full max-w-[650px] flex-col overflow-hidden rounded-3xl border border-[#60A5FA]/25 bg-[#10213A] shadow-2xl shadow-black/40">

                {{-- HEADER --}}
                <div class="border-b-4 border-[#60A5FA] bg-[#0D1B31] px-6 py-6 text-white">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <div class="flex items-center gap-3">
                                <svg class="h-6 w-6 text-[#60A5FA]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.957a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.367 2.446a1 1 0 00-.364 1.118l1.286 3.957c.3.921-.755 1.688-1.538 1.118l-3.367-2.446a1 1 0 00-1.176 0l-3.367 2.446c-.783.57-1.838-.197-1.538-1.118l1.286-3.957a1 1 0 00-.364-1.118L4.06 9.384c-.783-.57-.38-1.81.588-1.81H8.81a1 1 0 00.95-.69l1.286-3.957z"/>
                                </svg>

                                <h2 class="text-xl font-black uppercase tracking-wide text-[#F8FAFC]">
                                    Certification Logic
                                </h2>
                            </div>

                            <p class="mt-2 text-sm text-[#CBD5E1]">
                                Review the automated certificate distribution flow
                            </p>
                        </div>

                        <button
                            type="button"
                            onclick="closeCertificationLogicModal()"
                            class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full border border-[#60A5FA]/20 bg-[#13284A]/70 text-[#F8FAFC] transition hover:border-[#60A5FA]/50 hover:bg-[#13284A]"
                        >
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2.3" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- BODY --}}
                <div class="overflow-y-auto bg-[#10213A] px-7 py-7">
                    <p class="text-center text-sm font-black text-[#F8FAFC]">
                        Based on system rules, certificates will be issued dynamically:
                    </p>

                    <div class="relative mt-6 grid grid-cols-1 gap-5 md:grid-cols-2">
                        <div class="absolute left-1/2 top-1/2 hidden h-[3px] w-8 -translate-x-1/2 -translate-y-1/2 bg-[#60A5FA]/30 md:block"></div>

                        <section class="relative rounded-2xl border border-[#60A5FA]/20 bg-[#13284A]/60 p-6">
                            <div class="flex h-11 w-11 items-center justify-center rounded-2xl border border-[#FACC15]/40 bg-[#FACC15]/15 text-[#FACC15]">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2.3" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9 9 0 100-18 9 9 0 000 18z"/>
                                </svg>
                            </div>

                            <h3 class="mt-5 text-base font-black uppercase tracking-wide text-[#F8FAFC]">
                                Did Not Evaluate
                            </h3>

                            <p class="mt-2 text-sm leading-6 text-[#CBD5E1]">
                                Participant attended but did not submit an evaluation.
                            </p>

                            <div class="mt-5 flex items-center gap-3 rounded-2xl border border-[#60A5FA]/20 bg-[#0D1B31]/70 px-4 py-3">
                                <svg class="h-5 w-5 text-[#FACC15]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6M7 4h7l5 5v11a2 2 0 01-2 2H7a2 2 0 01-2-2V6a2 2 0 012-2z"/>
                                </svg>

                                <span class="text-sm font-black text-[#F8FAFC]">
                                    Certificate of Attendance
                                </span>
                            </div>
                        </section>

                        <section class="relative rounded-2xl border border-[#60A5FA]/20 bg-[#13284A]/60 p-6">
                            <div class="flex h-11 w-11 items-center justify-center rounded-2xl border border-[#22C55E]/40 bg-[#22C55E]/15 text-[#22C55E]">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2.3" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75l2 2 4-5"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9 9 0 100-18 9 9 0 000 18z"/>
                                </svg>
                            </div>

                            <h3 class="mt-5 text-base font-black uppercase tracking-wide text-[#F8FAFC]">
                                Completed Evaluation
                            </h3>

                            <p class="mt-2 text-sm leading-6 text-[#CBD5E1]">
                                Participant attended and successfully completed the evaluation form.
                            </p>

                            <div class="mt-5 space-y-3">
                                <div class="flex items-center gap-3 rounded-2xl border border-[#60A5FA]/20 bg-[#0D1B31]/70 px-4 py-3">
                                    <svg class="h-5 w-5 text-[#22C55E]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6M7 4h7l5 5v11a2 2 0 01-2 2H7a2 2 0 01-2-2V6a2 2 0 012-2z"/>
                                    </svg>

                                    <span class="text-sm font-black text-[#F8FAFC]">
                                        Certificate of Attendance
                                    </span>
                                </div>

                                <div class="flex items-center gap-3 rounded-2xl border border-[#22C55E]/40 bg-[#22C55E]/15 px-4 py-3">
                                    <svg class="h-5 w-5 text-[#22C55E]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 9a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l-1 9 4-3 4 3-1-9"/>
                                    </svg>

                                    <span class="text-sm font-black text-[#86EFAC]">
                                        Certificate of Participation
                                    </span>
                                </div>
                            </div>
                        </section>
                    </div>

                    <div class="mt-6 flex items-start gap-4 rounded-2xl border border-[#FACC15]/30 bg-[#FACC15]/10 px-5 py-4">
                        <svg class="mt-0.5 h-6 w-6 shrink-0 text-[#FACC15]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5"/>
                        </svg>

                        <p class="text-sm font-bold leading-6 text-[#F8FAFC]">
                            Clicking confirm will send an email blast with evaluation links to
                            <span id="certificationRecipientCount" class="font-black text-[#FACC15]">0</span> checked-in participants.
                        </p>
                    </div>
                </div>

                {{-- FOOTER --}}
                <div class="grid grid-cols-1 gap-4 border-t border-[#1E3357] bg-[#0D1B31] px-6 py-5 md:grid-cols-[1fr_1.8fr]">
                    <button
                        type="button"
                        onclick="closeCertificationLogicModal()"
                        class="h-14 rounded-2xl border border-[#60A5FA]/25 bg-[#13284A]/70 text-sm font-black uppercase tracking-wide text-[#F8FAFC] transition hover:border-[#60A5FA]/50 hover:bg-[#13284A]"
                    >
                        Cancel
                    </button>

                    <form id="certificationConfirmForm" action="#" method="POST">
                        @csrf
                        <button
                            type="submit"
                            class="flex h-14 w-full items-center justify-center gap-3 rounded-2xl bg-[#3B82F6] text-sm font-black uppercase tracking-wide text-white shadow-sm transition hover:bg-[#2563EB]"
                        >
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
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
    const manageEvaluationStatusMessage = document.getElementById('manageEvaluationStatusMessage');
    const managePostEventSection = document.getElementById('managePostEventSection');
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
    const createBannerImageInput = document.getElementById('createBannerImageInput');
    const createBannerImagePreview = document.getElementById('createBannerImagePreview');
    const createBannerUploadBox = document.getElementById('createBannerUploadBox');
    const createBannerUploadIcon = document.getElementById('createBannerUploadIcon');
    const createBannerUploadTitle = document.getElementById('createBannerUploadTitle');
    const createBannerUploadHint = document.getElementById('createBannerUploadHint');
    const createBannerFileName = document.getElementById('createBannerFileName');
    const editBannerImageInput = document.getElementById('editBannerImageInput');
    const editBannerUploadBox = document.getElementById('editBannerUploadBox');
    const editBannerUploadIcon = document.getElementById('editBannerUploadIcon');
    const editBannerUploadTitle = document.getElementById('editBannerUploadTitle');
    const editBannerUploadHint = document.getElementById('editBannerUploadHint');
    const editBannerFileName = document.getElementById('editBannerFileName');
    const editEventTitleLabel = document.getElementById('editEventTitleLabel');
    const eventsSearchInput = document.getElementById('eventsSearchInput');
    const eventsCategoryFilter = document.getElementById('eventsCategoryFilter');
    const eventsMonthFilter = document.getElementById('eventsMonthFilter');
    const eventsYearFilter = document.getElementById('eventsYearFilter');
    const eventCards = Array.from(document.querySelectorAll('[data-event-card]'));
    const eventsEmptyState = document.getElementById('eventsEmptyState');
    const eventsEmptyMessage = document.getElementById('eventsEmptyMessage');
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
            manageEventHero.style.backgroundImage = `linear-gradient(to right, rgba(13,27,49,0.97), rgba(13,27,49,0.82), rgba(13,27,49,0.62)), url('${eventData.image}')`;
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
        const statusKey = (eventData.statusKey || eventData.status || 'active').toLowerCase();
        const statusLabel = eventData.status || (statusKey.charAt(0).toUpperCase() + statusKey.slice(1));
        const isArchived = statusKey === 'archived';
        const isDone = statusKey === 'done';
        const alreadySent = String(eventData.evaluationAlreadySent || '0') === '1';

        if (manageEventStatusBadge) {
            manageEventStatusBadge.textContent = `• ${statusLabel}`;
            manageEventStatusBadge.classList.toggle('bg-[#22C55E]', !isArchived && !isDone);
            manageEventStatusBadge.classList.toggle('bg-[#EF4444]', isArchived);
            manageEventStatusBadge.classList.toggle('bg-[#64748B]', isDone);
        }
        if (managePostEventSection) {
            managePostEventSection.classList.toggle('hidden', !isDone);
        }
        if (manageSendEvaluationButton) {
            manageSendEvaluationButton.disabled = !isDone || alreadySent;
            manageSendEvaluationButton.classList.toggle('opacity-50', !isDone || alreadySent);
            manageSendEvaluationButton.classList.toggle('cursor-not-allowed', !isDone || alreadySent);
            manageSendEvaluationButton.classList.toggle('hidden', alreadySent);
            manageSendEvaluationButton.title = alreadySent
                ? 'Evaluation links already sent.'
                : (isDone ? '' : 'Post-event actions are available after the event is done.');
        }
        if (manageEvaluationStatusMessage) {
            manageEvaluationStatusMessage.classList.toggle('hidden', !alreadySent);
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

    function syncBannerPreview({
        input,
        preview,
        uploadBox,
        uploadIcon,
        uploadTitle,
        uploadHint,
        fileNameLabel,
        filledTitle,
        defaultTitle,
    }) {
        if (!input) return;

        const selectedFile = input.files && input.files[0] ? input.files[0] : null;
        if (!selectedFile) {
            if (fileNameLabel) {
                fileNameLabel.textContent = '';
                fileNameLabel.classList.add('hidden');
            }
            if (uploadTitle) {
                uploadTitle.textContent = defaultTitle;
            }
            if (uploadHint) {
                uploadHint.textContent = 'PNG, JPG, WEBP up to 5MB';
            }
            if (uploadIcon) {
                uploadIcon.classList.remove('hidden');
            }
            if (preview && preview.id === 'createBannerImagePreview') {
                preview.src = '';
                preview.classList.add('hidden');
            }
            if (uploadBox) {
                uploadBox.classList.remove('border-[#60A5FA]');
            }
            return;
        }

        if (fileNameLabel) {
            fileNameLabel.textContent = selectedFile.name;
            fileNameLabel.classList.remove('hidden');
        }
        if (uploadTitle) {
            uploadTitle.textContent = filledTitle;
        }
        if (uploadHint) {
            uploadHint.textContent = 'Image selected. Ready to upload.';
        }
        if (uploadIcon) {
            uploadIcon.classList.add('hidden');
        }
        if (uploadBox) {
            uploadBox.classList.add('border-[#60A5FA]');
        }

        if (preview) {
            const objectUrl = URL.createObjectURL(selectedFile);
            preview.src = objectUrl;
            preview.classList.remove('hidden');
        }
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

    function applyEventFilters() {
        const searchValue = String(eventsSearchInput?.value || '').trim().toLowerCase();
        const categoryValue = String(eventsCategoryFilter?.value || 'all');
        const monthValue = String(eventsMonthFilter?.value || 'all');
        const yearValue = String(eventsYearFilter?.value || 'all');
        let visibleCount = 0;

        eventCards.forEach(card => {
            const searchTarget = String(card.dataset.eventSearch || '').toLowerCase();
            const matchesSearch = searchValue === '' || searchTarget.includes(searchValue);
            const matchesCategory = categoryValue === 'all' || card.dataset.eventCategory === categoryValue;
            const matchesMonth = monthValue === 'all' || card.dataset.eventMonth === monthValue;
            const matchesYear = yearValue === 'all' || card.dataset.eventYear === yearValue;
            const shouldShow = matchesSearch && matchesCategory && matchesMonth && matchesYear;

            card.classList.toggle('hidden', !shouldShow);
            if (shouldShow) {
                visibleCount += 1;
            }
        });

        if (eventsEmptyState) {
            const showEmpty = visibleCount === 0;
            eventsEmptyState.classList.toggle('hidden', !showEmpty);
            if (eventsEmptyMessage) {
                eventsEmptyMessage.textContent = showEmpty
                    ? 'No events found.'
                    : '';
            }
        }
    }

    if (openCreateEventModalButton) {
        openCreateEventModalButton.addEventListener('click', openCreateEventModal);
    }

    if (eventsSearchInput) {
        eventsSearchInput.addEventListener('input', applyEventFilters);
    }
    if (eventsCategoryFilter) {
        eventsCategoryFilter.addEventListener('change', applyEventFilters);
    }
    if (eventsMonthFilter) {
        eventsMonthFilter.addEventListener('change', applyEventFilters);
    }
    if (eventsYearFilter) {
        eventsYearFilter.addEventListener('change', applyEventFilters);
    }
    applyEventFilters();

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
                statusKey: trigger.dataset.eventStatusKey,
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
                evaluationAlreadySent: trigger.dataset.evaluationAlreadySent,
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

    if (createBannerImageInput) {
        createBannerImageInput.addEventListener('change', () => {
            syncBannerPreview({
                input: createBannerImageInput,
                preview: createBannerImagePreview,
                uploadBox: createBannerUploadBox,
                uploadIcon: createBannerUploadIcon,
                uploadTitle: createBannerUploadTitle,
                uploadHint: createBannerUploadHint,
                fileNameLabel: createBannerFileName,
                filledTitle: 'Image selected',
                defaultTitle: 'Upload image',
            });
        });
    }

    if (editBannerImageInput) {
        editBannerImageInput.addEventListener('change', () => {
            syncBannerPreview({
                input: editBannerImageInput,
                preview: editCurrentImagePreview,
                uploadBox: editBannerUploadBox,
                uploadIcon: editBannerUploadIcon,
                uploadTitle: editBannerUploadTitle,
                uploadHint: editBannerUploadHint,
                fileNameLabel: editBannerFileName,
                filledTitle: 'New image selected',
                defaultTitle: 'Replace image',
            });
        });
    }

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

    // Prevent selecting past dates: set `min` on start/end date inputs to today
    (function() {
        const pad = (n) => String(n).padStart(2, '0');
        const now = new Date();
        const todayStr = `${now.getFullYear()}-${pad(now.getMonth() + 1)}-${pad(now.getDate())}`;

        const setMins = () => {
            const createStart = document.querySelector('#createEventModal input[name="start_date"]');
            const createEnd = document.querySelector('#createEventModal input[name="end_date"]');
            if (createStart) createStart.min = todayStr;
            if (createEnd) createEnd.min = todayStr;

            if (typeof editStartDateInput !== 'undefined' && editStartDateInput) editStartDateInput.min = todayStr;
            if (typeof editEndDateInput !== 'undefined' && editEndDateInput) editEndDateInput.min = todayStr;
        };

        // Apply immediately on load
        setMins();

        // Re-apply when opening modals (covers interactive flows)
        if (openCreateEventModalButton) {
            openCreateEventModalButton.addEventListener('click', setMins);
        }
        if (openEditEventModalFromManageButton) {
            openEditEventModalFromManageButton.addEventListener('click', setMins);
        }
    })();

    // View toggle (grid / list) with persistence
    (function() {
        const gridBtn = document.getElementById('gridViewButton');
        const listBtn = document.getElementById('listViewButton');
        const eventsContainer = document.getElementById('eventsContainer');

        const applyGrid = () => {
            if (!eventsContainer) return;
            eventsContainer.classList.remove('flex', 'flex-col');
            eventsContainer.classList.add('grid', 'grid-cols-1', 'gap-7', 'lg:grid-cols-2', '2xl:grid-cols-3');
            if (gridBtn) gridBtn.classList.add('bg-[#3B82F6]','text-white');
            if (listBtn) listBtn.classList.remove('bg-[#3B82F6]','text-white');
            localStorage.setItem('eventsView', 'grid');
            // remove list-view specific style
            document.body.classList.remove('events-list-view');
            // re-apply filters & batching so visibility is consistent
            applyEventFilters();
        };

        const applyList = () => {
            if (!eventsContainer) return;
            eventsContainer.classList.remove('grid', 'lg:grid-cols-2', '2xl:grid-cols-3');
            eventsContainer.classList.add('flex', 'flex-col', 'gap-4');
            if (listBtn) listBtn.classList.add('bg-[#3B82F6]','text-white');
            if (gridBtn) gridBtn.classList.remove('bg-[#3B82F6]','text-white');
            localStorage.setItem('eventsView', 'list');
            // add list-view body flag for CSS rules
            document.body.classList.add('events-list-view');
            // re-apply filters & batching so visibility is consistent
            applyEventFilters();
        };

        if (gridBtn) gridBtn.addEventListener('click', applyGrid);
        if (listBtn) listBtn.addEventListener('click', applyList);

        // on load, apply stored preference
        const pref = localStorage.getItem('eventsView') || 'grid';
        if (pref === 'list') {
            applyList();
        } else {
            applyGrid();
        }

        // Inject CSS tweaks for list view
        const listStyle = document.createElement('style');
        listStyle.innerHTML = `
            body.events-list-view #eventsContainer article{ display:flex; gap:1rem; align-items:center; }
            body.events-list-view #eventsContainer article .relative.h-[190px]{ min-width:160px; height:120px; flex:0 0 160px; }
            body.events-list-view #eventsContainer article img{ height:100%; width:100%; object-fit:cover }
            /* ensure filter/batch hidden classes still hide items in list view */
            body.events-list-view #eventsContainer article.hidden{ display:none !important; }
            body.events-list-view #eventsContainer article.batch-hidden{ display:none !important; }
        `;
        document.head.appendChild(listStyle);
    })();

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
