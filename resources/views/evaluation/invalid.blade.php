<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Evaluation Unavailable — NU Lipa EMS</title>
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

        <article class="eval-card eval-status-card">
            <div class="eval-status-icon" style="color:#ef4444;" aria-hidden="true">
                <svg viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="9"></circle>
                    <path d="M8 8l8 8M16 8l-8 8"></path>
                </svg>
            </div>
            <h2>Evaluation Unavailable</h2>
            <p>{{ $message }}</p>
            <a href="{{ url('/') }}" class="eval-home-link">Return to Home</a>
        </article>
    </main>
</body>
</html>
