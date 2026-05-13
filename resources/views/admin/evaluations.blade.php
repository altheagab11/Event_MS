@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-[#0F1E36] via-[#132B4A] to-[#0F1E36] font-sans text-[#F8FAFC]">
    <div class="flex">

        @include('admin.partials.sidebar')

        {{-- MAIN --}}
        <main class="ml-[270px] min-h-screen w-full">

            @include('admin.partials.topbar', ['topbarTitle' => 'Evaluations'])

            <section class="px-9 py-10">

                {{-- Page Header --}}
                <div class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
                    <div>
                        <div class="flex items-center gap-3">
                            <div class="h-7 w-1 rounded-full bg-[#60A5FA]"></div>
                            <h1 class="text-[28px] font-black tracking-tight text-[#F8FAFC]">
                                EVENT EVALUATIONS
                            </h1>
                        </div>

                        <p class="mt-2 text-sm text-[#CBD5E1]">
                            Review feedback and ratings from event participants.
                        </p>
                    </div>

                    {{-- Average Rating Badge --}}
                    <div class="inline-flex items-center gap-3 self-start rounded-2xl border border-[#60A5FA]/25 bg-[#1E375A]/65 px-5 py-3 shadow-lg shadow-black/20 backdrop-blur-md">
                        <svg class="h-5 w-5 fill-[#60A5FA] text-[#60A5FA]" viewBox="0 0 24 24">
                            <path d="M12 2.5l2.9 5.88 6.49.94-4.7 4.58 1.11 6.46L12 17.3l-5.8 3.06 1.11-6.46-4.7-4.58 6.49-.94L12 2.5z"/>
                        </svg>

                        <span class="text-sm font-black text-[#F8FAFC]">
                            {{ $averageRating !== null ? number_format($averageRating, 1) : '—' }}
                        </span>
                        <span class="text-sm font-bold text-[#CBD5E1]">Avg. Rating</span>
                    </div>
                </div>

                {{-- Evaluations Grid --}}
                <section class="mt-14 grid grid-cols-1 gap-7 md:grid-cols-2 xl:grid-cols-3">
                    @forelse ($evaluations as $evaluation)
                        <article class="rounded-2xl border bg-[#1E375A]/65 p-7 shadow-sm transition hover:border-[#60A5FA]/40
                            {{ $loop->first ? 'border-[#60A5FA]/45' : 'border-[#60A5FA]/20' }}">

                            <div class="flex items-start justify-between gap-5">
                                <div class="flex items-center gap-4">
                                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-[#3B82F6] text-base font-black text-white">
                                        {{ $evaluation['avatar'] }}
                                    </div>

                                    <div>
                                        <h2 class="text-xl font-black text-[#F8FAFC]">
                                            {{ $evaluation['reviewer_name'] }}
                                        </h2>
                                        <p class="mt-1 text-sm text-[#94A3B8]">
                                            {{ $evaluation['date'] }}
                                        </p>
                                    </div>
                                </div>

                                <div class="text-right">
                                    <div class="flex items-center justify-end gap-0.5">
                                        @for ($i = 1; $i <= 5; $i++)
                                            @if ($i <= $evaluation['rating'])
                                                <svg class="h-5 w-5 fill-[#60A5FA] text-[#60A5FA]" viewBox="0 0 24 24">
                                                    <path d="M12 2.5l2.9 5.88 6.49.94-4.7 4.58 1.11 6.46L12 17.3l-5.8 3.06 1.11-6.46-4.7-4.58 6.49-.94L12 2.5z"/>
                                                </svg>
                                            @else
                                                <svg class="h-5 w-5 fill-none text-[#475569]" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 2.5l2.9 5.88 6.49.94-4.7 4.58 1.11 6.46L12 17.3l-5.8 3.06 1.11-6.46-4.7-4.58 6.49-.94L12 2.5z"/>
                                                </svg>
                                            @endif
                                        @endfor
                                    </div>

                                    <p class="mt-1 text-sm font-black text-[#60A5FA]">
                                        {{ $evaluation['rating'] }}/5
                                    </p>
                                </div>
                            </div>

                            <p class="mt-6 text-sm font-black uppercase tracking-widest text-[#60A5FA]">
                                {{ $evaluation['event_name'] }}
                            </p>

                            <div class="mt-3 border-l-2 border-[#60A5FA]/35 pl-4">
                                <p class="line-clamp-2 text-sm italic leading-6 text-[#CBD5E1]">
                                    "{{ $evaluation['comment_preview'] }}"
                                </p>
                            </div>

                            <div class="mt-5 border-t border-[#1E3357] pt-5">
                                <div class="flex justify-end">
                                    <button
                                        type="button"
                                        data-evaluation-id="{{ $evaluation['id'] }}"
                                        class="text-sm font-black uppercase tracking-wide text-[#F8FAFC] transition hover:text-[#60A5FA]"
                                    >
                                        Read Full Review →
                                    </button>
                                </div>
                            </div>
                        </article>
                    @empty
                        <article class="rounded-2xl border border-[#60A5FA]/20 bg-[#1E375A]/65 p-12 text-center shadow-lg shadow-black/20 backdrop-blur-md md:col-span-2 xl:col-span-3">
                            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl border border-[#60A5FA]/30 bg-[#0D1B31]/60">
                                <svg class="h-8 w-8 text-[#60A5FA]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 10h8M8 14h5M5 5h14v12H7l-4 4V7a2 2 0 012-2z"/>
                                </svg>
                            </div>
                            <p class="mt-5 text-lg font-black text-[#F8FAFC]">No evaluations yet</p>
                            <p class="mt-2 text-sm text-[#94A3B8]">
                                Submitted participant evaluations will appear here.
                            </p>
                        </article>
                    @endforelse
                </section>
            </section>
        </main>
    </div>
</div>

{{-- Full review modal --}}
<div
    id="evaluationReviewModal"
    class="fixed inset-0 z-50 hidden items-center justify-center bg-black/70 px-4 py-6 backdrop-blur-sm"
    onclick="if (event.target === this) closeEvaluationModal()"
>
    <div class="relative w-full max-w-[620px] overflow-hidden rounded-3xl border border-[#60A5FA]/25 bg-[#10213A] shadow-2xl shadow-black/40" onclick="event.stopPropagation()">

        <div class="flex items-start justify-between border-b border-[#1E3357] bg-[#0D1B31] px-6 py-6 text-white">
            <div>
                <h2 class="text-2xl font-black uppercase tracking-wide text-[#F8FAFC]">
                    Full Review
                </h2>
                <p id="evaluationModalEvent" class="mt-2 text-sm text-[#60A5FA]">
                    Event name
                </p>
            </div>

            <button
                type="button"
                onclick="closeEvaluationModal()"
                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full border border-[#60A5FA]/20 bg-[#13284A]/70 text-[#F8FAFC] transition hover:border-[#60A5FA]/50 hover:bg-[#13284A]"
            >
                <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2.3" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <div class="px-7 py-7">
            <section class="rounded-2xl border border-[#60A5FA]/20 bg-[#13284A]/60 p-6">
                <div class="flex items-start justify-between gap-5">
                    <div class="flex items-center gap-4">
                        <div
                            id="evaluationModalInitial"
                            class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-[#3B82F6] text-xl font-black text-white"
                        >
                            M
                        </div>

                        <div>
                            <h3 id="evaluationModalName" class="text-xl font-black text-[#F8FAFC]">
                                Maria Santos
                            </h3>
                            <p id="evaluationModalDate" class="mt-1 text-sm text-[#94A3B8]">
                                2026-04-21
                            </p>
                        </div>
                    </div>

                    <div class="text-right">
                        <div id="evaluationModalStars" class="flex items-center justify-end gap-0.5"></div>
                        <p id="evaluationModalRating" class="mt-1 text-sm font-black text-[#60A5FA]">
                            5/5
                        </p>
                    </div>
                </div>

                <div class="my-6 border-t border-[#1E3357]"></div>

                <p class="text-sm font-black uppercase tracking-widest text-[#60A5FA]">
                    Review Feedback
                </p>

                <p id="evaluationModalReview" class="mt-3 whitespace-pre-wrap text-base italic leading-8 text-[#CBD5E1]">
                    Review text
                </p>

                <div id="evaluationModalExtras" class="mt-6 hidden">
                    <p class="text-sm font-black uppercase tracking-widest text-[#60A5FA]">
                        Additional Responses
                    </p>
                    <div id="evaluationModalExtrasList" class="mt-3 space-y-3 text-sm text-[#CBD5E1]"></div>
                </div>
            </section>

            <div class="mt-5 flex justify-end">
                <button
                    type="button"
                    onclick="closeEvaluationModal()"
                    class="rounded-2xl border border-[#60A5FA]/25 bg-[#13284A]/70 px-8 py-3 text-sm font-black uppercase tracking-wide text-[#F8FAFC] transition hover:border-[#60A5FA]/50 hover:bg-[#13284A]"
                >
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    const evaluationRows = @json($evaluations->values());
    const evaluationMap = new Map(evaluationRows.map(row => [String(row.id), row]));
    const evaluationModal = document.getElementById('evaluationReviewModal');
    const evaluationModalStars = document.getElementById('evaluationModalStars');
    const evaluationModalName = document.getElementById('evaluationModalName');
    const evaluationModalInitial = document.getElementById('evaluationModalInitial');
    const evaluationModalDate = document.getElementById('evaluationModalDate');
    const evaluationModalEvent = document.getElementById('evaluationModalEvent');
    const evaluationModalRating = document.getElementById('evaluationModalRating');
    const evaluationModalReview = document.getElementById('evaluationModalReview');
    const evaluationModalExtras = document.getElementById('evaluationModalExtras');
    const evaluationModalExtrasList = document.getElementById('evaluationModalExtrasList');

    function renderModalStars(rating) {
        if (!evaluationModalStars) return;
        evaluationModalStars.innerHTML = '';

        for (let i = 1; i <= 5; i++) {
            if (i <= rating) {
                evaluationModalStars.innerHTML += `
                    <svg class="h-5 w-5 fill-[#60A5FA] text-[#60A5FA]" viewBox="0 0 24 24">
                        <path d="M12 2.5l2.9 5.88 6.49.94-4.7 4.58 1.11 6.46L12 17.3l-5.8 3.06 1.11-6.46-4.7-4.58 6.49-.94L12 2.5z"/>
                    </svg>
                `;
            } else {
                evaluationModalStars.innerHTML += `
                    <svg class="h-5 w-5 fill-none text-[#475569]" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 2.5l2.9 5.88 6.49.94-4.7 4.58 1.11 6.46L12 17.3l-5.8 3.06 1.11-6.46-4.7-4.58 6.49-.94L12 2.5z"/>
                    </svg>
                `;
            }
        }
    }

    function renderModalExtras(extras) {
        if (!evaluationModalExtras || !evaluationModalExtrasList) return;
        const hasExtras = Array.isArray(extras) && extras.length > 0;
        evaluationModalExtras.classList.toggle('hidden', !hasExtras);
        evaluationModalExtrasList.innerHTML = '';

        if (!hasExtras) return;

        extras.forEach(item => {
            const label = String(item.label || '').trim();
            const value = String(item.value || '').trim();
            if (!label && !value) return;

            const row = document.createElement('div');
            row.className = 'flex flex-wrap items-start gap-2';
            const labelEl = document.createElement('span');
            labelEl.className = 'font-black text-[#F8FAFC]';
            labelEl.textContent = label !== '' ? label + ':' : 'Response:';
            const valueEl = document.createElement('span');
            valueEl.textContent = value !== '' ? value : 'Not provided';
            row.append(labelEl, valueEl);
            evaluationModalExtrasList.append(row);
        });
    }

    function openEvaluationModalById(evaluationId) {
        if (!evaluationModal) return;
        const evaluation = evaluationMap.get(String(evaluationId));

        if (!evaluation) {
            if (evaluationModalName) evaluationModalName.textContent = 'Review not found';
            if (evaluationModalInitial) evaluationModalInitial.textContent = '?';
            if (evaluationModalDate) evaluationModalDate.textContent = '—';
            if (evaluationModalEvent) evaluationModalEvent.textContent = '—';
            if (evaluationModalRating) evaluationModalRating.textContent = '0/5';
            if (evaluationModalReview) evaluationModalReview.textContent = 'Review not found.';
            renderModalStars(0);
            renderModalExtras([]);
        } else {
            const rating = parseInt(String(evaluation.rating), 10) || 0;
            const scoreLabel = evaluation.score !== undefined && evaluation.score !== null && String(evaluation.score).trim() !== ''
                ? String(evaluation.score)
                : String(rating);
            if (evaluationModalName) evaluationModalName.textContent = evaluation.reviewer_name || 'Unknown Reviewer';
            if (evaluationModalInitial) evaluationModalInitial.textContent = evaluation.avatar || '?';
            if (evaluationModalDate) evaluationModalDate.textContent = evaluation.date || '—';
            if (evaluationModalEvent) evaluationModalEvent.textContent = evaluation.event_name || 'Unknown Event';
            if (evaluationModalRating) evaluationModalRating.textContent = scoreLabel + '/5';
            if (evaluationModalReview) {
                const reviewText = evaluation.comment_full && String(evaluation.comment_full).trim() !== ''
                    ? evaluation.comment_full
                    : 'No comment provided.';
                evaluationModalReview.textContent = '"' + reviewText + '"';
            }
            renderModalStars(rating);
            renderModalExtras(evaluation.additional_answers || []);
        }

        evaluationModal.classList.remove('hidden');
        evaluationModal.classList.add('flex');
        document.body.classList.add('overflow-hidden');
    }

    function closeEvaluationModal() {
        if (!evaluationModal) return;
        evaluationModal.classList.add('hidden');
        evaluationModal.classList.remove('flex');
        document.body.classList.remove('overflow-hidden');
    }

    window.closeEvaluationModal = closeEvaluationModal;

    const readButtons = Array.from(document.querySelectorAll('[data-evaluation-id]'));
    readButtons.forEach(button => {
        button.addEventListener('click', () => openEvaluationModalById(button.dataset.evaluationId));
    });
</script>
@endsection
