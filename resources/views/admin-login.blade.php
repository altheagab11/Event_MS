<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin Login | Event Management System</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/landingpage.css') }}">
</head>

<body class="login-page">
  <div class="admin-portal open" aria-hidden="false">

    {{-- Header: brand only (matches landing page) --}}
    <header class="topbar">
      <div class="container topbar-inner">
        <a href="{{ url('/') }}" class="brand" aria-label="Event Management System home">
          <span class="brand-icon" aria-hidden="true">
            <svg viewBox="0 0 24 24" role="img" focusable="false">
              <rect x="3.5" y="5.5" width="17" height="15" rx="2.5"></rect>
              <line x1="3.5" y1="9" x2="20.5" y2="9"></line>
              <line x1="8" y1="3.5" x2="8" y2="7"></line>
              <line x1="16" y1="3.5" x2="16" y2="7"></line>
            </svg>
          </span>
          <span class="brand-text">
            <span class="brand-name">Event Management System</span>
            <span class="brand-tag">Academic Events Portal</span>
          </span>
        </a>
      </div>
    </header>
    
    <div class="admin-portal-main">
      <div class="admin-card">
        <div class="admin-card-icon" aria-hidden="true">
          <svg viewBox="0 0 24 24" role="img" focusable="false">
            <path d="M12 3l7 3v5c0 4.8-3 8.6-7 10-4-1.4-7-5.2-7-10V6l7-3z"></path>
            <path d="M9.5 12l2 2 3.5-3.5"></path>
          </svg>
        </div>
        <h2>Admin Portal</h2>
        <p>Secure access to your admin dashboard</p>

        @if (session('status'))
        <div style="margin-bottom: 12px; padding: 10px 12px; border: 1px solid #86efac; border-radius: 8px; background: #f0fdf4; color: #166534; font-size: 13px;">
          {{ session('status') }}
        </div>
        @endif

        @if ($errors->any())
        <div style="margin-bottom: 12px; padding: 10px 12px; border: 1px solid #ef9a9a; border-radius: 8px; background: #fff3f3; color: #8b1c1c; font-size: 13px;">
          @foreach ($errors->all() as $error)
          <div>{{ $error }}</div>
          @endforeach
        </div>
        @endif

        <form class="admin-form" action="{{ route('admin.login.store') }}" method="post">
          @csrf
          <label class="admin-input-wrap">
            <span class="admin-input-icon" aria-hidden="true">
              <svg viewBox="0 0 24 24" role="img" focusable="false">
                <rect x="3.5" y="6.5" width="17" height="11" rx="2.5"></rect>
                <path d="M4.5 8 12 13l7.5-5"></path>
              </svg>
            </span>
            <input type="email" name="email" value="{{ old('email') }}" placeholder="admin@example.com" autocomplete="username" required>
          </label>
          <label class="admin-input-wrap">
            <span class="admin-input-icon" aria-hidden="true">
              <svg viewBox="0 0 24 24" role="img" focusable="false">
                <rect x="4" y="11" width="16" height="9" rx="2"></rect>
                <path d="M8 11V8.5a4 4 0 1 1 8 0V11"></path>
              </svg>
            </span>
            <input type="password" name="password" placeholder="Password" autocomplete="current-password" required>
          </label>
          <div style="margin: -4px 0 12px; text-align: right;">
            <a href="{{ route('password.request') }}" style="font-size: 12px; color: #1d4ed8; text-decoration: none;">Forgot password?</a>
          </div>
          <button type="submit" class="admin-access-btn">Access Dashboard</button>
        </form>
      </div>
    </div>

    {{-- Footer (compact, matches landing page brand) --}}
    <footer class="footer" aria-label="Site footer">
      <div class="container">
        <div class="footer-bottom">
          <div class="fbrand">
            <span class="fbrand-icon" aria-hidden="true">
              <svg viewBox="0 0 24 24" role="img" focusable="false">
                <rect x="3.5" y="5.5" width="17" height="15" rx="2.5"></rect>
                <line x1="3.5" y1="9" x2="20.5" y2="9"></line>
                <line x1="8" y1="3.5" x2="8" y2="7"></line>
                <line x1="16" y1="3.5" x2="16" y2="7"></line>
              </svg>
            </span>
            <span class="fbrand-text">
              <span class="fbrand-name">Event Management System</span>
              <span class="fbrand-tag">Academic Events Portal</span>
            </span>
          </div>
          <p class="fcopy">© {{ date('Y') }} Event Management System. All rights reserved.</p>
        </div>
      </div>
    </footer>
  </div>
</body>

</html>
