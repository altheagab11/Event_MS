<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check In — {{ $eventName }} — NU Lipa EMS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/participant-evaluation.css') }}">
    <link rel="stylesheet" href="{{ asset('css/participant-attendance.css') }}">
</head>
<body>
    <main class="eval-page">
        <div class="eval-brand">
            <span class="eval-brand-badge" aria-hidden="true">NU</span>
            <h1>NU Lipa Event Management</h1>
        </div>

        <article class="eval-card">
            <header class="eval-card-header">
                <h2>Online Attendance Check-In</h2>
                <div class="eval-meta">
                    <div class="eval-meta-row">
                        <span class="eval-meta-label">Event</span>
                        <span class="eval-meta-value">{{ $eventName }}</span>
                    </div>
                    <div class="eval-meta-row">
                        <span class="eval-meta-label">Schedule</span>
                        <span class="eval-meta-value">{{ $eventDate }}</span>
                    </div>
                </div>
            </header>

            <div class="eval-card-body">
                <p class="eval-intro">
                    Enter the email address you used when registering for this event. Your attendance will be recorded as <strong>Present</strong> with mode <strong>Online</strong>.
                </p>

                @if ($errors->any())
                    <div class="eval-alert eval-alert-error" role="alert">
                        {{ $errors->first('email') }}
                    </div>
                @endif

                <form method="POST" action="{{ $submitUrl }}" novalidate>
                    @csrf
                    <div class="eval-question">
                        <label class="eval-question-label" for="attendance-email">Registered Email</label>
                        <input
                            id="attendance-email"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            autocomplete="email"
                            class="eval-text-input"
                            placeholder="you@example.com"
                        >
                    </div>
                    <button type="submit" class="eval-submit">Check In</button>
                </form>
            </div>
        </article>
    </main>
</body>
</html>
