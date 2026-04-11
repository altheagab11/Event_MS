<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>NU Lipa EMS</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --navy: #173568;
            --navy-dark: #132f5c;
            --navy-soft: #2b4a80;
            --gold: #f1bf65;
            --light: #f5f6fa;
            --card: #365286;
            --border-soft: rgba(255, 255, 255, 0.12);
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: "Poppins", sans-serif;
            background: var(--navy);
            color: var(--light);
        }

        .topbar {
            height: 52px;
            background: var(--navy);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }

        .topbar-inner {
            width: min(1280px, 100%);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--gold);
            font-weight: 700;
            letter-spacing: 0.1px;
        }

        .brand svg {
            width: 20px;
            height: 20px;
        }

        .top-actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .pill {
            border: 0;
            border-radius: 9px;
            padding: 8px 14px;
            font: 600 14px/1 "Poppins", sans-serif;
            cursor: pointer;
        }

        .pill-muted {
            background: #5d789c;
            color: #f4f8ff;
        }

        .pill-gold {
            background: var(--gold);
            color: #173568;
        }

        .hero {
            position: relative;
            min-height: 380px;
            background-image:
                linear-gradient(rgba(18, 44, 87, 0.62), rgba(18, 44, 87, 0.62)),
                url("https://images.unsplash.com/photo-1469474968028-56623f02e42e?auto=format&fit=crop&w=1800&q=80");
            background-size: cover;
            background-position: center;
            display: grid;
            place-items: center;
            text-align: center;
            padding: 80px 16px;
        }

        .hero h1 {
            margin: 0;
            font-size: 36px;
            line-height: 1.1;
            font-weight: 800;
            color: #ffffff;
        }

        .hero h1 span {
            color: var(--gold);
        }

        .hero p {
            max-width: 672px;
            margin: 16px auto 32px;
            color: #f0dcb8;
            font-size: 20px;
            font-weight: 500;
            line-height: 1.4;
        }

        .hero-cta {
            border: 0;
            border-radius: 999px;
            background: var(--gold);
            color: #143463;
            font: 700 18px/1 "Poppins", sans-serif;
            padding: 12px 24px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
        }

        .section {
            background: var(--navy);
            padding: 48px 16px;
        }

        .container {
            width: min(1280px, 100%);
            margin: 0 auto;
        }

        .section-title {
            margin: 0 0 32px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 30px;
            line-height: 1.1;
            font-weight: 700;
        }

        .cards {
            display: grid;
            grid-template-columns: 1fr;
            gap: 24px;
        }

        .card {
            background: var(--card);
            border: 1px solid var(--border-soft);
            border-radius: 16px;
            padding: 24px;
            min-height: 260px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .card h3 {
            margin: 0 0 10px;
            font-size: 20px;
            line-height: 1.2;
            color: #ffffff;
        }

        .card h3.highlight {
            color: var(--gold);
        }

        .card p {
            margin: 0;
            color: #d8e1f5;
            font-size: 14px;
            line-height: 1.625;
            font-weight: 500;
        }

        .card-btn {
            margin-top: 14px;
            border: 1px solid rgba(255, 255, 255, 0.25);
            background: transparent;
            color: #f6f8ff;
            border-radius: 12px;
            padding: 12px 16px;
            font: 700 16px/1 "Poppins", sans-serif;
            cursor: pointer;
        }

        .card-btn.gold {
            border: 0;
            background: var(--gold);
            color: #163668;
        }

        .discover {
            background: #e8cfad;
            color: #173568;
            padding: 72px 16px 64px;
        }

        .discover-heading {
            text-align: center;
            margin-bottom: 22px;
        }

        .discover-heading h2 {
            margin: 0;
            font-size: 42px;
            font-weight: 700;
        }

        .discover-heading p {
            margin: 10px 0 0;
            font-size: 29px;
            color: #2f4e80;
        }

        .discover-filters {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 12px;
            margin-bottom: 32px;
            flex-wrap: wrap;
        }

        .filter-group {
            display: flex;
            gap: 6px;
            background: #eef2f6;
            border: 1px solid #d8dfe8;
            border-radius: 16px;
            padding: 6px;
        }

        .filter-pill {
            border: 0;
            border-radius: 12px;
            padding: 10px 18px;
            font: 700 14px/1 "Poppins", sans-serif;
            color: #547792;
            background: transparent;
        }

        .filter-pill.active {
            background: #1a3263;
            color: #ffffff;
        }

        .filter-month {
            border: 1px solid #d8dfe8;
            border-radius: 14px;
            background: #ffffff;
            color: #1a3263;
            font: 700 14px/1 "Poppins", sans-serif;
            padding: 12px 20px;
        }

        .events-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 22px;
        }

        .event-card {
            background: #f5f7fb;
            border: 2px solid #547792;
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 4px 4px 0 #547792;
            display: flex;
            flex-direction: column;
        }

        .event-image {
            height: 175px;
            object-fit: cover;
            width: 100%;
        }

        .event-body {
            padding: 18px 22px 20px;
            display: flex;
            flex-direction: column;
            min-height: 280px;
        }

        .event-title {
            margin: 0 0 8px;
            color: #1a3263;
            font-size: 18px;
            line-height: 1.25;
            font-weight: 700;
        }

        .event-meta {
            margin: 0;
            color: #547792;
            font-size: 13px;
            line-height: 1.45;
        }

        .event-desc {
            margin: 12px 0 18px;
            color: #2e4e80;
            font-size: 14px;
            line-height: 1.5;
            flex-grow: 1;
        }

        .event-btn {
            border: 0;
            border-radius: 10px;
            background: var(--gold);
            color: #173568;
            font: 700 16px/1 "Poppins", sans-serif;
            padding: 12px 16px;
        }

        .event-btn.outline {
            background: #ffffff;
            color: #1a3263;
            border: 1px solid #6a89ad;
        }

        .site-footer {
            background: #1a3263;
            color: #f0dcb8;
            padding: 24px 16px;
        }

        .site-footer-inner {
            width: min(1280px, 100%);
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
        }

        .footer-brand {
            font-size: 31px;
            font-weight: 700;
            color: var(--gold);
        }

        .footer-copy {
            font-size: 13px;
            color: #e4d0af;
        }

        @media (min-width: 640px) {
            .hero {
                padding: 84px 24px;
            }

            .section {
                padding: 48px 24px;
            }
        }

        @media (min-width: 768px) {
            .hero {
                padding-top: 96px;
                padding-bottom: 96px;
            }

            .hero h1 {
                font-size: 60px;
            }

            .hero p {
                font-size: 24px;
            }

            .cards {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }

            .events-grid {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
        }

        @media (min-width: 1024px) {
            .hero {
                padding-left: 32px;
                padding-right: 32px;
            }

            .section {
                padding-left: 32px;
                padding-right: 32px;
            }
        }

        @media (max-width: 900px) {
            .topbar {
                height: auto;
                padding: 10px 14px;
            }

            .topbar-inner {
                flex-wrap: wrap;
                gap: 10px;
            }

            .discover-heading h2 {
                font-size: 32px;
            }

            .discover-heading p {
                font-size: 18px;
            }

            .site-footer-inner {
                flex-direction: column;
                align-items: flex-start;
            }

            .footer-brand {
                font-size: 22px;
            }
        }
    </style>
</head>
<body>
    <header class="topbar">
        <div class="topbar-inner">
            <div class="brand">
                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <rect x="3" y="4" width="18" height="17" rx="2" stroke="currentColor" stroke-width="2"/>
                    <path d="M8 2V6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    <path d="M16 2V6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    <path d="M3 10H21" stroke="currentColor" stroke-width="2"/>
                </svg>
                <span>NU Lipa EMS</span>
            </div>

            <div class="top-actions">
                <button class="pill pill-muted">Home / Events</button>
                <button class="pill pill-gold">Admin Login</button>
            </div>
        </div>
    </header>

    <section class="hero">
        <div>
            <h1>Welcome to <span>NU Lipa Events</span></h1>
            <p>
                The unified platform for typical school events and research conferences.
                Register, participate, and evaluate all in one place.
            </p>
            <button class="hero-cta">View Upcoming Events <span aria-hidden="true">&rarr;</span></button>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <h2 class="section-title">
                <span aria-hidden="true">&#128276;</span>
                <span>Latest Announcements</span>
            </h2>

            <div class="cards">
                <article class="card">
                    <div>
                        <h3 class="highlight">&#128227; Call for Papers: Tech Innovations Summit</h3>
                        <p>
                            Call for Papers for the Tech Innovations Summit is now open! Register and
                            submit your PDF manuscripts until May 1, 2026.
                        </p>
                    </div>
                    <button class="card-btn gold">Register &amp; Submit Paper</button>
                </article>

                <article class="card">
                    <div>
                        <h3>&#127936; Intramurals 2026 Team Registration</h3>
                        <p>
                            Team registrations for Basketball and Volleyball are now ongoing at the
                            Student Council Office.
                        </p>
                    </div>
                    <button class="card-btn">View Intramurals</button>
                </article>

                <article class="card">
                    <div>
                        <h3>&#128221; Foundation Day Reminders</h3>
                        <p>
                            Attendance is mandatory for all freshmen and sophomores. Please register
                            early to secure your event kit.
                        </p>
                    </div>
                    <button class="card-btn gold">Register Now</button>
                </article>
            </div>
        </div>
    </section>

    <section class="discover">
        <div class="container">
            <div class="discover-heading">
                <h2>Discover Events</h2>
                <p>Browse through school gatherings and major conferences.</p>
            </div>

            <div class="discover-filters">
                <div class="filter-group">
                    <button class="filter-pill active">All</button>
                    <button class="filter-pill">School</button>
                    <button class="filter-pill">Conference</button>
                </div>
                <button class="filter-month">Filter by Month</button>
            </div>

            <div class="events-grid">
                <article class="event-card">
                    <img class="event-image" src="https://images.unsplash.com/photo-1765474604988-4fc3fa14f46b?auto=format&fit=crop&w=1080&q=80" alt="Foundation Day">
                    <div class="event-body">
                        <h3 class="event-title">NU Lipa Foundation Day 2026</h3>
                        <p class="event-meta">April 20, 2026<br>Main Campus Grounds</p>
                        <p class="event-desc">Join us as we celebrate another year of excellence and commitment to nation-building with booths, games, and performances.</p>
                        <button class="event-btn">Register Now</button>
                    </div>
                </article>

                <article class="event-card">
                    <img class="event-image" src="https://images.unsplash.com/photo-1582192904915-d89c7250b235?auto=format&fit=crop&w=1080&q=80" alt="Tech Summit">
                    <div class="event-body">
                        <h3 class="event-title">Tech Innovations Summit</h3>
                        <p class="event-meta">May 15, 2026<br>Auditorium Hall B</p>
                        <p class="event-desc">A national conference gathering students and professionals to discuss the latest trends in software and AI. Call for papers is open.</p>
                        <button class="event-btn">Register Now</button>
                    </div>
                </article>

                <article class="event-card">
                    <img class="event-image" src="https://images.unsplash.com/photo-1721339040530-be9c4eff0360?auto=format&fit=crop&w=1080&q=80" alt="Intramurals">
                    <div class="event-body">
                        <h3 class="event-title">University Intramurals 2026</h3>
                        <p class="event-meta">June 5-8, 2026<br>University Gymnasium &amp; Field</p>
                        <p class="event-desc">Show your college pride! The annual University Intramurals is back with exciting basketball, volleyball, e-sports, and cheering competitions.</p>
                        <button class="event-btn">Register Now</button>
                    </div>
                </article>

                <article class="event-card">
                    <img class="event-image" src="https://images.unsplash.com/photo-1709086566111-dfa0699a6842?auto=format&fit=crop&w=1080&q=80" alt="Sining at Kultura">
                    <div class="event-body">
                        <h3 class="event-title">Sining at Kultura Festival</h3>
                        <p class="event-meta">August 12, 2026<br>Open Amphitheater</p>
                        <p class="event-desc">A celebration of Filipino heritage through traditional dances, spoken word poetry, and visual arts exhibitions.</p>
                        <button class="event-btn">Register Now</button>
                    </div>
                </article>

                <article class="event-card">
                    <img class="event-image" src="https://images.unsplash.com/photo-1773828755374-0ee802d9f44b?auto=format&fit=crop&w=1080&q=80" alt="Medical Symposium">
                    <div class="event-body">
                        <h3 class="event-title">Medical &amp; Allied Health Research Symposium</h3>
                        <p class="event-meta">September 22, 2026<br>College of Allied Health Sciences Bldg</p>
                        <p class="event-desc">Presenting breakthrough researches in Nursing, Pharmacy, and Medical Technology. Open for professional and student researchers nationwide.</p>
                        <button class="event-btn">Register Now</button>
                    </div>
                </article>

                <article class="event-card">
                    <img class="event-image" src="https://images.unsplash.com/photo-1664273891579-22f28332f3c4?auto=format&fit=crop&w=1080&q=80" alt="Leadership Seminar">
                    <div class="event-body">
                        <h3 class="event-title">Leadership Seminar Series</h3>
                        <p class="event-meta">March 10, 2026<br>Student Lounge</p>
                        <p class="event-desc">Empowering the next generation of leaders. (Event concluded)</p>
                        <button class="event-btn outline">Evaluate Event</button>
                    </div>
                </article>
            </div>
        </div>
    </section>

    <footer class="site-footer">
        <div class="site-footer-inner">
            <div class="footer-brand">NU Lipa Event Management System</div>
            <div class="footer-copy">© 2026 NU Lipa. All rights reserved. For typical and conference events.</div>
        </div>
    </footer>
</body>
</html>
