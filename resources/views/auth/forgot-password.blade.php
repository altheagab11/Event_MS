<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Forgot Password | Event Management System</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/landingpage.css') }}">
</head>
<body class="login-page">
    <div class="admin-portal open" aria-hidden="false">
        <header class="topbar">
            <div class="container topbar-inner">
                <a href="{{ url('/') }}" class="brand" aria-label="Event Management System home">
                    <span class="brand-text">
                        <span class="brand-name">Event Management System</span>
                        <span class="brand-tag">Academic Events Portal</span>
                    </span>
                </a>
            </div>
        </header>

        <div class="admin-portal-main">
            <div class="admin-card">
                <h2>Forgot Password</h2>
                <p>Enter your portal email and we will send a reset link.</p>

                @if (session('status'))
                    <div style="margin-bottom:12px;padding:10px 12px;border:1px solid #86efac;border-radius:8px;background:#f0fdf4;color:#166534;font-size:13px;">
                        {{ session('status') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div style="margin-bottom:12px;padding:10px 12px;border:1px solid #ef9a9a;border-radius:8px;background:#fff3f3;color:#8b1c1c;font-size:13px;">
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <form class="admin-form" action="{{ route('password.email') }}" method="post">
                    @csrf
                    <label class="admin-input-wrap">
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="admin@example.com" required autofocus>
                    </label>
                    <button type="submit" class="admin-access-btn">Send Reset Link</button>
                </form>

                <p style="margin-top:14px;font-size:13px;text-align:center;">
                    <a href="{{ route('admin.login') }}">Back to login</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
