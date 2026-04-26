@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#F6F8FB] font-sans text-[#111827]">
    <div class="flex">

        {{-- SIDEBAR (match dashboard / participants) --}}
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

        {{-- MAIN --}}
        <main class="ml-[270px] min-h-screen w-full bg-[#F6F8FB]">

            <header class="flex h-[78px] items-center justify-between border-b border-[#E5EAF1] bg-white px-9">
                <h2 class="text-sm font-black uppercase tracking-widest text-[#111827]">
                    Evaluations
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

            <div class="px-9 py-10">

                <div class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
                    <div>
                        <div class="flex items-center gap-3">
                            <div class="h-7 w-1 rounded-full bg-[#D2A64B]"></div>
                            <h1 class="text-[28px] font-black tracking-tight text-[#111827]">
                                EVENT EVALUATIONS
                            </h1>
                        </div>

                        <p class="mt-2 text-sm text-[#53657F]">
                            Review feedback and ratings from event participants.
                        </p>
                    </div>

                    <div class="inline-flex items-center gap-3 self-start rounded-2xl border border-[#DDE6F2] bg-white px-5 py-3">
                        <svg class="h-5 w-5 fill-[#D2A64B] text-[#D2A64B]" viewBox="0 0 24 24">
                            <path d="M12 2.5l2.9 5.88 6.49.94-4.7 4.58 1.11 6.46L12 17.3l-5.8 3.06 1.11-6.46-4.7-4.58 6.49-.94L12 2.5z"/>
                        </svg>

                        <span class="text-sm font-black text-[#111827]">
                            {{ $averageRating !== null ? number_format($averageRating, 1) : '—' }}
                        </span>
                        <span class="text-sm font-bold text-[#53657F]">Avg. Rating</span>
                    </div>
                </div>

                <section class="mt-14 grid grid-cols-1 gap-7 md:grid-cols-2 xl:grid-cols-3">
                    @forelse ($evaluations as $evaluation)
                        @php
                            $reviewBody = $evaluation['comment_full'] !== '' ? $evaluation['comment_full'] : 'No comment provided.';
                            $modalPayload = [
                                'name' => $evaluation['reviewer_name'],
                                'initial' => $evaluation['avatar'],
                                'date' => $evaluation['date'],
                                'event' => $evaluation['event_name'],
                                'rating' => $evaluation['rating'],
                                'review' => $reviewBody,
                            ];
                        @endphp
                        <article class="rounded-2xl border bg-white p-7 transition hover:-translate-y-1 hover:shadow-md
                            {{ $loop->first ? 'border-[#D2B06A]' : 'border-[#DDE6F2]' }}">

                            <div class="flex items-start justify-between gap-5">
                                <div class="flex items-center gap-4">
                                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-[#172233] text-base font-black text-white">
                                        {{ $evaluation['avatar'] }}
                                    </div>

                                    <div>
                                        <h2 class="text-xl font-black text-[#111827]">
                                            {{ $evaluation['reviewer_name'] }}
                                        </h2>
                                        <p class="mt-1 text-sm text-[#64748B]">
                                            {{ $evaluation['date'] }}
                                        </p>
                                    </div>
                                </div>

                                <div class="text-right">
                                    <div class="flex items-center justify-end gap-0.5">
                                        @for ($i = 1; $i <= 5; $i++)
                                            @if ($i <= $evaluation['rating'])
                                                <svg class="h-5 w-5 fill-[#D2B06A] text-[#D2B06A]" viewBox="0 0 24 24">
                                                    <path d="M12 2.5l2.9 5.88 6.49.94-4.7 4.58 1.11 6.46L12 17.3l-5.8 3.06 1.11-6.46-4.7-4.58 6.49-.94L12 2.5z"/>
                                                </svg>
                                            @else
                                                <svg class="h-5 w-5 fill-none text-[#CBD5E1]" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 2.5l2.9 5.88 6.49.94-4.7 4.58 1.11 6.46L12 17.3l-5.8 3.06 1.11-6.46-4.7-4.58 6.49-.94L12 2.5z"/>
                                                </svg>
                                            @endif
                                        @endfor
                                    </div>

                                    <p class="mt-1 text-sm font-black text-[#C8A25A]">
                                        {{ $evaluation['rating'] }}/5
                                    </p>
                                </div>
                            </div>

                            <p class="mt-6 text-sm font-black uppercase tracking-widest text-[#C8A25A]">
                                {{ $evaluation['event_name'] }}
                            </p>

                            <div class="mt-3 border-l-2 border-[#DDE6F2] pl-4">
                                <p class="line-clamp-2 text-sm italic leading-6 text-[#64748B]">
                                    "{{ $evaluation['comment_preview'] }}"
                                </p>
                            </div>

                            <div class="mt-5 border-t border-[#E8EEF5] pt-5">
                                <div class="flex justify-end">
                                    <button
                                        type="button"
                                        onclick='openEvaluationModal({{ \Illuminate\Support\Js::from($modalPayload) }})'
                                        class="text-sm font-black uppercase tracking-wide text-[#111827] transition hover:text-[#C8A25A]"
                                    >
                                        Read Full Review →
                                    </button>
                                </div>
                            </div>
                        </article>
                    @empty
                        <article class="rounded-2xl border border-[#DDE6F2] bg-white p-12 text-center md:col-span-2 xl:col-span-3">
                            <p class="text-lg font-black text-[#111827]">No evaluations yet</p>
                            <p class="mt-2 text-sm text-[#64748B]">
                                Submitted participant evaluations will appear here.
                            </p>
                        </article>
                    @endforelse
                </section>
            </div>
        </main>
    </div>
</div>

{{-- Full review modal --}}
<div
    id="evaluationReviewModal"
    class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 px-4 py-6 backdrop-blur-sm"
    onclick="if (event.target === this) closeEvaluationModal()"
>
    <div class="relative w-full max-w-[620px] overflow-hidden rounded-3xl border border-[#DDE6F2] bg-white shadow-2xl" onclick="event.stopPropagation()">

        <div class="flex items-start justify-between bg-[#172233] px-6 py-6 text-white">
            <div>
                <h2 class="text-2xl font-black uppercase tracking-wide">
                    Full Review
                </h2>
                <p id="evaluationModalEvent" class="mt-2 text-sm text-white/70">
                    Event name
                </p>
            </div>

            <button
                type="button"
                onclick="closeEvaluationModal()"
                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-white/10 text-white transition hover:bg-white/20"
            >
                <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2.3" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <div class="px-7 py-7">
            <section class="rounded-2xl border border-[#DDE6F2] bg-[#F8FAFC] p-6">
                <div class="flex items-start justify-between gap-5">
                    <div class="flex items-center gap-4">
                        <div
                            id="evaluationModalInitial"
                            class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-[#172233] text-xl font-black text-white"
                        >
                            M
                        </div>

                        <div>
                            <h3 id="evaluationModalName" class="text-xl font-black text-[#111827]">
                                Maria Santos
                            </h3>
                            <p id="evaluationModalDate" class="mt-1 text-sm text-[#64748B]">
                                2026-04-21
                            </p>
                        </div>
                    </div>

                    <div class="text-right">
                        <div id="evaluationModalStars" class="flex items-center justify-end gap-0.5"></div>
                        <p id="evaluationModalRating" class="mt-1 text-sm font-black text-[#C8A25A]">
                            5/5
                        </p>
                    </div>
                </div>

                <div class="my-6 border-t border-[#DDE6F2]"></div>

                <p class="text-sm font-black uppercase tracking-widest text-[#C8A25A]">
                    Review Feedback
                </p>

                <p id="evaluationModalReview" class="mt-3 whitespace-pre-wrap text-base italic leading-8 text-[#53657F]">
                    Review text
                </p>
            </section>

            <div class="mt-5 flex justify-end">
                <button
                    type="button"
                    onclick="closeEvaluationModal()"
                    class="rounded-2xl border border-[#DDE6F2] bg-[#F8FAFC] px-8 py-3 text-sm font-black uppercase tracking-wide text-[#111827] transition hover:bg-[#EEF3F9]"
                >
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    function openEvaluationModal(data) {
        const modal = document.getElementById('evaluationReviewModal');
        const starsContainer = document.getElementById('evaluationModalStars');
        const rating = parseInt(String(data.rating), 10) || 0;

        document.getElementById('evaluationModalName').textContent = data.name;
        document.getElementById('evaluationModalInitial').textContent = data.initial;
        document.getElementById('evaluationModalDate').textContent = data.date;
        document.getElementById('evaluationModalEvent').textContent = data.event;
        document.getElementById('evaluationModalRating').textContent = rating + '/5';
        document.getElementById('evaluationModalReview').textContent = '"' + data.review + '"';

        starsContainer.innerHTML = '';

        for (let i = 1; i <= 5; i++) {
            if (i <= rating) {
                starsContainer.innerHTML += `
                    <svg class="h-5 w-5 fill-[#D2B06A] text-[#D2B06A]" viewBox="0 0 24 24">
                        <path d="M12 2.5l2.9 5.88 6.49.94-4.7 4.58 1.11 6.46L12 17.3l-5.8 3.06 1.11-6.46-4.7-4.58 6.49-.94L12 2.5z"/>
                    </svg>
                `;
            } else {
                starsContainer.innerHTML += `
                    <svg class="h-5 w-5 fill-none text-[#CBD5E1]" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 2.5l2.9 5.88 6.49.94-4.7 4.58 1.11 6.46L12 17.3l-5.8 3.06 1.11-6.46-4.7-4.58 6.49-.94L12 2.5z"/>
                    </svg>
                `;
            }
        }

        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.classList.add('overflow-hidden');
    }

    function closeEvaluationModal() {
        const modal = document.getElementById('evaluationReviewModal');

        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.classList.remove('overflow-hidden');
    }
</script>
@endsection
