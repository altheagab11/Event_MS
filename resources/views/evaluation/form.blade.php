<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Evaluate {{ $eventName }} — NU Lipa EMS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/participant-evaluation.css') }}">
</head>
<body>
    <main class="eval-page">
        <div class="eval-brand">
            <span class="eval-brand-badge" aria-hidden="true">NU</span>
            <h1>NU Lipa Event Management</h1>
        </div>

        <article class="eval-card">
            <header class="eval-card-header">
                <h2>Event Evaluation</h2>
                <div class="eval-meta">
                    <div class="eval-meta-row">
                        <span class="eval-meta-label">Event</span>
                        <span class="eval-meta-value">{{ $eventName }}</span>
                    </div>
                    <div class="eval-meta-row">
                        <span class="eval-meta-label">Date</span>
                        <span class="eval-meta-value">{{ $eventDate }}</span>
                    </div>
                    <div class="eval-meta-row">
                        <span class="eval-meta-label">Participant</span>
                        <span class="eval-meta-value">{{ $participantName }}</span>
                    </div>
                </div>
            </header>

            <div class="eval-card-body">
                <p class="eval-intro">
                    Thank you for attending. Please rate your experience below. Your feedback helps us improve future events.
                </p>

                @if ($errors->any())
                    <div class="eval-alert eval-alert-error" role="alert">
                        {{ $errors->first('form') ?: $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ $submitUrl }}" id="evaluationForm" novalidate>
                    @csrf

                    @foreach ($questions as $question)
                        @if ($question->question_type === 'rating')
                            <section class="eval-question" data-question-id="{{ $question->question_id }}">
                                <label class="eval-question-label" for="rating-{{ $question->question_id }}">
                                    {{ $question->question_text }}
                                    @if ($question->is_required)
                                        <span aria-hidden="true">*</span>
                                    @endif
                                </label>

                                <input
                                    type="hidden"
                                    name="ratings[{{ $question->question_id }}]"
                                    id="rating-{{ $question->question_id }}"
                                    value="{{ old('ratings.'.$question->question_id) }}"
                                    @if ($question->is_required) data-required-rating="1" @endif
                                >

                                <div class="eval-stars" role="radiogroup" aria-label="{{ $question->question_text }}">
                                    @for ($star = 1; $star <= 5; $star++)
                                        <button
                                            type="button"
                                            class="rating-star"
                                            data-question="{{ $question->question_id }}"
                                            data-rate="{{ $star }}"
                                            aria-label="{{ $star }} star{{ $star > 1 ? 's' : '' }}"
                                        >
                                            <svg viewBox="0 0 24 24" aria-hidden="true">
                                                <polygon points="12,4.5 14.4,9.4 19.8,10.2 15.9,14 16.8,19.4 12,16.9 7.2,19.4 8.1,14 4.2,10.2 9.6,9.4"></polygon>
                                            </svg>
                                        </button>
                                    @endfor
                                </div>
                            </section>
                        @endif
                    @endforeach

                    <label class="eval-label" for="comment">Additional comments (optional)</label>
                    <textarea
                        class="eval-text"
                        id="comment"
                        name="comment"
                        rows="4"
                        placeholder="What did you like? What can we improve?"
                    >{{ old('comment') }}</textarea>

                    <button type="submit" class="eval-submit" id="evalSubmitBtn" disabled>
                        Submit Evaluation
                    </button>
                </form>
            </div>
        </article>
    </main>

    <script>
        (function () {
            const form = document.getElementById('evaluationForm');
            const submitBtn = document.getElementById('evalSubmitBtn');
            const requiredInputs = Array.from(document.querySelectorAll('[data-required-rating="1"]'));
            const starButtons = Array.from(document.querySelectorAll('.rating-star'));
            const ratings = {};

            requiredInputs.forEach((input) => {
                const questionId = input.id.replace('rating-', '');
                const initial = Number.parseInt(input.value || '0', 10);
                if (!Number.isNaN(initial) && initial > 0) {
                    ratings[questionId] = initial;
                }
            });

            function paintStars(questionId, activeValue) {
                starButtons
                    .filter((button) => button.dataset.question === questionId)
                    .forEach((button) => {
                        const rate = Number.parseInt(button.dataset.rate || '0', 10);
                        button.classList.toggle('active', rate <= activeValue);
                    });
            }

            function syncSubmitState() {
                const allAnswered = requiredInputs.every((input) => {
                    const questionId = input.id.replace('rating-', '');
                    const value = Number.parseInt(ratings[questionId] || '0', 10);
                    return value >= 1 && value <= 5;
                });
                submitBtn.disabled = !allAnswered;
            }

            requiredInputs.forEach((input) => {
                const questionId = input.id.replace('rating-', '');
                const initial = Number.parseInt(ratings[questionId] || '0', 10);
                if (initial > 0) {
                    paintStars(questionId, initial);
                }
            });
            syncSubmitState();

            starButtons.forEach((button) => {
                const questionId = button.dataset.question;
                const rate = Number.parseInt(button.dataset.rate || '0', 10);

                button.addEventListener('mouseenter', () => paintStars(questionId, rate));
                button.addEventListener('mouseleave', () => {
                    paintStars(questionId, Number.parseInt(ratings[questionId] || '0', 10));
                });
                button.addEventListener('click', () => {
                    ratings[questionId] = rate;
                    const input = document.getElementById(`rating-${questionId}`);
                    if (input) {
                        input.value = String(rate);
                    }
                    paintStars(questionId, rate);
                    syncSubmitState();
                });
            });

            form.addEventListener('submit', () => {
                submitBtn.disabled = true;
                submitBtn.textContent = 'Submitting...';
            });
        })();
    </script>
</body>
</html>
