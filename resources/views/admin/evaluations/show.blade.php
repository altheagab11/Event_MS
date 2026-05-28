@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-[#0F1E36] via-[#132B4A] to-[#0F1E36] font-sans text-[#F8FAFC]">
    <div class="flex">
        @include('admin.partials.sidebar')

        <main class="ml-[270px] min-h-screen w-full">
            @include('admin.partials.topbar', ['topbarTitle' => 'Evaluation Insights'])

            <section class="px-4 py-8 sm:px-6 md:px-8 md:py-10">
                <div class="mx-auto w-full max-w-6xl space-y-6">
                    <div class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
                        <div class="min-w-0">
                            <a
                                href="{{ route('admin.evaluations') }}"
                                class="inline-flex items-center gap-2 text-sm font-bold text-[#93C5FD] transition hover:text-[#BFDBFE]"
                            >
                                <span aria-hidden="true">←</span>
                                Back to Evaluations
                            </a>

                            <div class="mt-3 flex items-center gap-3">
                                <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-[#60A5FA]/25 bg-[#0D1B31]/60 text-[#93C5FD]">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 19.5h16M7 16V9m5 7V5m5 11v-4"/>
                                    </svg>
                                </span>
                                <h1 class="text-3xl font-black tracking-tight text-[#F8FAFC]">
                                    Evaluation Insights
                                </h1>
                            </div>
                            <p class="mt-2 max-w-2xl text-sm text-[#CBD5E1]">
                                Detailed insights and feedback from the seminar evaluation.
                            </p>
                        </div>

                        <div class="inline-flex items-center gap-3 self-start rounded-2xl border border-[#60A5FA]/25 bg-[#1E375A]/70 px-5 py-3 shadow-lg shadow-black/20">
                            <span class="inline-flex h-7 w-7 items-center justify-center rounded-lg bg-[#0D1B31]/60 text-[#FCD34D]">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12 2.5l2.9 5.88 6.49.94-4.7 4.58 1.11 6.46L12 17.3l-5.8 3.06 1.11-6.46-4.7-4.58 6.49-.94L12 2.5z"/>
                                </svg>
                            </span>
                            <span class="text-xs font-black uppercase tracking-wider text-[#93C5FD]">Average Rating</span>
                            <span class="text-lg font-black text-[#F8FAFC]">{{ number_format($averageRating, 1) }} / 5</span>
                        </div>
                    </div>

                    <section class="rounded-2xl border border-[#60A5FA]/20 bg-[#13284A]/60 p-5 shadow-lg shadow-black/15 sm:p-6">
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">
                            <article class="min-w-0 rounded-xl border border-[#60A5FA]/20 bg-[#0D1B31]/45 p-4 xl:col-span-1">
                                <p class="flex items-center gap-2 text-xs font-black uppercase tracking-wider text-[#93C5FD]">
                                    <svg class="h-4 w-4 text-[#60A5FA]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <circle cx="12" cy="12" r="9"></circle>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 7v5l3 3"></path>
                                    </svg>
                                    Overall Result
                                </p>
                                <p class="mt-2 text-3xl font-black text-[#F8FAFC]">{{ number_format($averageRating, 1) }} <span class="text-lg text-[#93C5FD]">/ 5</span></p>
                                <p class="mt-2 text-xs leading-5 text-[#CBD5E1]">
                                    Based on {{ $totalResponses }} question responses.
                                </p>
                                <p class="mt-1 text-xs leading-5 text-[#94A3B8]">
                                    Submitted on {{ $evaluation['date'] ?? '—' }}
                                </p>
                            </article>

                            <article class="min-w-0 rounded-xl border border-[#60A5FA]/20 bg-[#0D1B31]/45 p-4 xl:col-span-1">
                                <p class="flex items-center gap-2 text-xs font-black uppercase tracking-wider text-[#93C5FD]">
                                    <svg class="h-4 w-4 text-[#F97316]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                                    </svg>
                                    Lowest Rated Areas
                                </p>
                                <div class="mt-3 space-y-2">
                                    @forelse($lowestRated as $item)
                                        <div class="rounded-lg border border-[#EF4444]/35 bg-[#3b1220]/35 px-3 py-2">
                                            <p class="break-words text-sm font-semibold text-[#FCA5A5]">{{ $item['question_text'] }}</p>
                                            <p class="mt-1 text-xs font-bold text-[#FECACA]">{{ $item['rating_value'] }}/5</p>
                                        </div>
                                    @empty
                                        <p class="text-sm text-[#94A3B8]">No low-rated areas detected.</p>
                                    @endforelse
                                </div>
                            </article>

                            <article class="min-w-0 rounded-xl border border-[#60A5FA]/20 bg-[#0D1B31]/45 p-4 md:col-span-2 xl:col-span-2">
                                <p class="flex items-center gap-2 text-xs font-black uppercase tracking-wider text-[#93C5FD]">
                                    <svg class="h-4 w-4 text-[#22C55E]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4"></path>
                                        <circle cx="12" cy="12" r="9"></circle>
                                    </svg>
                                    Strengths
                                </p>
                                <div class="mt-3 space-y-2">
                                    @forelse($strengths as $item)
                                        <div class="rounded-lg border border-[#22C55E]/35 bg-[#052e1e]/35 px-3 py-2">
                                            <p class="break-words text-sm font-semibold text-[#86EFAC]">{{ $item['question_text'] }}</p>
                                            <p class="mt-1 text-xs font-bold text-[#BBF7D0]">{{ $item['rating_value'] }}/5</p>
                                        </div>
                                    @empty
                                        <p class="text-sm text-[#94A3B8]">No strong positive items yet.</p>
                                    @endforelse
                                </div>
                            </article>
                        </div>

                        <article class="mt-4 min-w-0 rounded-xl border border-[#60A5FA]/20 bg-[#0D1B31]/45 p-4">
                            <p class="flex items-center gap-2 text-xs font-black uppercase tracking-wider text-[#93C5FD]">
                                <svg class="h-4 w-4 text-[#60A5FA]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.5 12.5 11 14l3.5-3.5M12 3l7 3v5c0 4.8-3 8.6-7 10-4-1.4-7-5.2-7-10V6l7-3z"/>
                                </svg>
                                Recommendation
                            </p>
                            <p class="mt-2 break-words text-sm leading-7 text-[#CBD5E1]">
                                {{ $recommendation }}
                            </p>
                        </article>
                    </section>

                    <section class="rounded-2xl border border-[#60A5FA]/20 bg-[#13284A]/60 p-5 shadow-lg shadow-black/15 sm:p-6">
                        <p class="text-xs font-black uppercase tracking-wider text-[#93C5FD]">Respondent Information</p>
                        <div class="mt-3 grid grid-cols-1 gap-3 md:grid-cols-2">
                            <div class="min-w-0 rounded-xl border border-[#60A5FA]/15 bg-[#0D1B31]/40 p-4">
                                <p class="text-xs uppercase tracking-wide text-[#94A3B8]">Name</p>
                                <p class="mt-1 break-words text-sm font-semibold text-[#F8FAFC]">{{ $evaluation['participant_name'] }}</p>
                            </div>
                            <div class="min-w-0 rounded-xl border border-[#60A5FA]/15 bg-[#0D1B31]/40 p-4">
                                <p class="text-xs uppercase tracking-wide text-[#94A3B8]">Email</p>
                                <p class="mt-1 break-all text-sm font-semibold text-[#F8FAFC]">{{ $evaluation['participant_email'] }}</p>
                            </div>
                            <div class="min-w-0 rounded-xl border border-[#60A5FA]/15 bg-[#0D1B31]/40 p-4">
                                <p class="text-xs uppercase tracking-wide text-[#94A3B8]">Submission Date</p>
                                <p class="mt-1 text-sm font-semibold text-[#F8FAFC]">{{ $evaluation['date'] ?? '—' }}</p>
                            </div>
                            <div class="min-w-0 rounded-xl border border-[#60A5FA]/15 bg-[#0D1B31]/40 p-4">
                                <p class="text-xs uppercase tracking-wide text-[#94A3B8]">Comment / Feedback</p>
                                <p class="mt-1 break-words text-sm leading-6 text-[#CBD5E1]">
                                    {{ trim((string) ($evaluation['comment_full'] ?? '')) !== '' ? $evaluation['comment_full'] : 'No comment provided.' }}
                                </p>
                            </div>
                        </div>
                    </section>

                    <section class="rounded-2xl border border-[#60A5FA]/20 bg-[#13284A]/60 p-5 shadow-lg shadow-black/15 sm:p-6">
                        <div class="flex items-center justify-between gap-3">
                            <h2 class="flex items-center gap-2 text-lg font-black text-[#F8FAFC]">
                                <svg class="h-5 w-5 text-[#60A5FA]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6M9 16h6M8 4h8a2 2 0 0 1 2 2v12l-4-2-4 2-4-2-4 2V6a2 2 0 0 1 2-2h4z"/>
                                </svg>
                                Question Responses
                            </h2>
                            <span class="text-xs font-bold uppercase tracking-wider text-[#93C5FD]">{{ $totalResponses }} Responses</span>
                        </div>

                        <div class="mt-4 space-y-3">
                            @forelse($answers as $index => $item)
                                @php
                                    $score = (int) ($item['rating_value'] ?? 0);
                                    $scoreLabel = $item['display_value'] ?? 'Not provided';
                                    $scoreClass = 'border-[#60A5FA]/35 bg-[#1E3A8A]/35 text-[#BFDBFE]';
                                    if ($score > 0 && $score <= 2) {
                                        $scoreClass = 'border-[#F97316]/40 bg-[#431407]/45 text-[#FDBA74]';
                                    } elseif ($score >= 4) {
                                        $scoreClass = 'border-[#22C55E]/35 bg-[#052e1e]/45 text-[#86EFAC]';
                                    }
                                @endphp
                                <article class="min-w-0 rounded-xl border border-[#60A5FA]/15 bg-[#0D1B31]/40 p-4">
                                    <div class="flex min-w-0 items-start gap-3">
                                        <span class="mt-0.5 inline-flex h-7 w-7 shrink-0 items-center justify-center rounded-full border border-[#60A5FA]/30 bg-[#13284A] text-xs font-black text-[#BFDBFE]">
                                            {{ $index + 1 }}
                                        </span>

                                        <div class="min-w-0 flex-1">
                                            <p class="break-words text-sm font-semibold leading-6 text-[#F8FAFC]">
                                                {{ $item['question_text'] }}
                                            </p>
                                        </div>

                                        <span class="shrink-0 rounded-lg border px-2.5 py-1 text-xs font-black {{ $scoreClass }}">
                                            {{ $scoreLabel }}
                                        </span>
                                    </div>
                                </article>
                            @empty
                                <article class="rounded-xl border border-[#60A5FA]/15 bg-[#0D1B31]/40 p-4">
                                    <p class="text-sm text-[#94A3B8]">No question responses available.</p>
                                </article>
                            @endforelse
                        </div>
                    </section>
                </div>
            </section>
        </main>
    </div>
</div>
@endsection
