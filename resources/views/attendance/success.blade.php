<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checked In — Event Management System</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/participant-evaluation.css') }}">
    <link rel="stylesheet" href="{{ asset('css/participant-attendance.css') }}">
</head>
<body>
    <main class="eval-page">
        <div class="eval-brand">
            <span class="eval-brand-badge" aria-hidden="true">EMS</span>
            <h1>Event Management System</h1>
        </div>

        <article class="eval-card eval-status-card">
            <div class="eval-status-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="9"></circle>
                    <path d="M8.5 12.3 10.8 14.6 15.8 9.6"></path>
                </svg>
            </div>
            <h2>You're Checked In, {{ $participantName }}!</h2>
            <p>
                Your attendance for <strong>{{ $eventName }}</strong> has been recorded as <strong>Present</strong>
                ({{ $attendanceMode }}@if ($checkInTime !== '') at {{ $checkInTime }}@endif).
            </p>
            <a href="{{ url('/') }}" class="eval-home-link">Return to Home</a>
        </article>
    </main>
</body>
</html>
