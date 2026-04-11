<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login | NU Lipa EMS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/landingpage.css') }}">
</head>
<body class="login-page">
    <div class="admin-portal open" aria-hidden="false">
        <header class="topbar">
            <div class="container topbar-inner">
                <div class="brand" aria-label="NU Lipa EMS">
                    <span class="brand-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" role="img" focusable="false">
                            <rect x="3.5" y="5.5" width="17" height="15" rx="2.5"></rect>
                            <line x1="3.5" y1="9" x2="20.5" y2="9"></line>
                            <line x1="8" y1="3.5" x2="8" y2="7"></line>
                            <line x1="16" y1="3.5" x2="16" y2="7"></line>
                        </svg>
                    </span>
                    <span class="brand-text">NU Lipa EMS</span>
                </div>
                <div class="top-actions">
                    <a href="{{ url('/') }}" class="pill pill-muted" style="text-decoration:none;">Home / Events</a>
                    <button type="button" class="pill pill-gold admin-btn">
                        <span class="pill-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" role="img" focusable="false">
                                <path d="M12 3l7 3v5c0 4.8-3 8.6-7 10-4-1.4-7-5.2-7-10V6l7-3z"></path>
                                <path d="M9.5 12l2 2 3.5-3.5"></path>
                            </svg>
                        </span>
                        <span>Admin Login</span>
                    </button>
                </div>
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
                <p>Secure access to NU Lipa EMS</p>

                <form class="admin-form" onsubmit="return false;">
                    <label class="admin-input-wrap">
                        <span class="admin-input-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" role="img" focusable="false">
                                <rect x="3.5" y="6.5" width="17" height="11" rx="2.5"></rect>
                                <path d="M4.5 8 12 13l7.5-5"></path>
                            </svg>
                        </span>
                        <input type="email" placeholder="admin@nu.edu.ph" value="admin@nu.edu.ph" readonly>
                    </label>
                    <label class="admin-input-wrap">
                        <span class="admin-input-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" role="img" focusable="false">
                                <rect x="4" y="11" width="16" height="9" rx="2"></rect>
                                <path d="M8 11V8.5a4 4 0 1 1 8 0V11"></path>
                            </svg>
                        </span>
                        <input type="password" placeholder="password" value="password" readonly>
                    </label>
                    <button type="button" class="admin-access-btn">Access Dashboard</button>
                </form>
            </div>
        </div>

        <footer class="footer">
            <div class="container footer-inner">
                <div class="fbrand" aria-label="NU Lipa Event Management System">
                    <span class="fbrand-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" role="img" focusable="false">
                            <rect x="3.5" y="5.5" width="17" height="15" rx="2.5"></rect>
                            <line x1="3.5" y1="9" x2="20.5" y2="9"></line>
                            <line x1="8" y1="3.5" x2="8" y2="7"></line>
                            <line x1="16" y1="3.5" x2="16" y2="7"></line>
                        </svg>
                    </span>
                    <span class="fbrand-text">NU Lipa Event Management System</span>
                </div>
                <div class="fcopy">© 2026 NU Lipa. All rights reserved. For typical and conference events.</div>
            </div>
        </footer>
    </div>
</body>
</html>
