<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Evaluate {{ $eventName }} — Event Management System</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/participant-evaluation.css') }}">
</head>
<body>
    <main class="eval-page">
        <div class="eval-brand">
            <span class="eval-brand-badge" aria-hidden="true">EMS</span>
            <h1>Event Management System</h1>
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

                    @php
                        $ratingQuestions = $questions->where('question_type', 'rating')->values();
                        $useStructuredTemplate = $ratingQuestions->count() >= 12;
                        $sectionHeadings = [
                            0 => '1. Seminar / Training Content',
                            3 => '2. Presentation / Speaker',
                            6 => '3. Logistics & Organization',
                            9 => '4. Overall Experience',
                        ];
                    @endphp

                    <div class="eval-scale" aria-hidden="true">
                        <span>Strongly Agree</span>
                        <span>Agree</span>
                        <span>Neutral</span>
                        <span>Disagree</span>
                        <span>Strongly Disagree</span>
                    </div>

                    @foreach ($ratingQuestions as $index => $question)
                        @if ($useStructuredTemplate && isset($sectionHeadings[$index]))
                            <h3 class="eval-section-title">{{ $sectionHeadings[$index] }}</h3>
                        @endif

                        <section class="eval-question" data-question-id="{{ $question->question_id }}">
                            <p class="eval-question-label">
                                {{ $question->question_text }}
                                @if ($question->is_required)
                                    <span aria-hidden="true">*</span>
                                @endif
                            </p>

                            <div class="eval-options" role="radiogroup" aria-label="{{ $question->question_text }}">
                                @for ($score = 5; $score >= 1; $score--)
                                    <label class="eval-option">
                                        <input
                                            type="radio"
                                            name="ratings[{{ $question->question_id }}]"
                                            value="{{ $score }}"
                                            @checked((int) old('ratings.'.$question->question_id) === $score)
                                            @if ($question->is_required) data-required-rating="1" @endif
                                        >
                                        <span class="sr-only">
                                            @switch($score)
                                                @case(5)
                                                    Strongly Agree
                                                    @break
                                                @case(4)
                                                    Agree
                                                    @break
                                                @case(3)
                                                    Neutral
                                                    @break
                                                @case(2)
                                                    Disagree
                                                    @break
                                                @default
                                                    Strongly Disagree
                                            @endswitch
                                        </span>
                                    </label>
                                @endfor
                            </div>
                        </section>
                    @endforeach

                    <label class="eval-label" for="comment">Comments/Suggestions</label>
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
            const requiredGroups = Array.from(document.querySelectorAll('[data-question-id]'))
                .filter((section) => section.querySelector('[data-required-rating="1"]'));

            function syncSubmitState() {
                const allAnswered = requiredGroups.every((section) => {
                    const checked = section.querySelector('input[type="radio"]:checked');

                    return checked !== null;
                });
                submitBtn.disabled = !allAnswered;
            }

            form.addEventListener('change', syncSubmitState);
            syncSubmitState();

            form.addEventListener('submit', () => {
                submitBtn.disabled = true;
                submitBtn.textContent = 'Submitting...';
            });
        })();
    </script>
</body>
</html>
