<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Evaluations | NU Lipa EMS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --blue-900: #183d7e;
            --gold: #f5c36c;
            --ink: #12356b;
            --bg: #f2f3f8;
            --line: #23457e;
            --text: #1a3462;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background: var(--bg);
            color: var(--text);
        }

        .layout {
            min-height: 100vh;
            display: grid;
            grid-template-columns: 210px 1fr;
        }

        .sidebar {
            border-right: 1px solid #e3e5ef;
            background: #f6f7fb;
            padding: 22px 16px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .brand {
            font-weight: 800;
            color: var(--ink);
            font-size: 28px;
            line-height: 1;
            letter-spacing: .4px;
        }

        .brand small {
            margin-top: 6px;
            display: inline-block;
            font-size: 11px;
            font-weight: 700;
            background: #112f67;
            color: #fff;
            border-radius: 4px;
            padding: 4px 8px;
            line-height: 1;
        }

        .menu-title {
            margin: 28px 4px 10px;
            color: #9ca6be;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: .8px;
        }

        .menu {
            display: grid;
            gap: 8px;
        }

        .menu a {
            text-decoration: none;
            color: #354f80;
            font-size: 16px;
            font-weight: 700;
            border-radius: 14px;
            padding: 12px 14px;
            display: flex;
            align-items: center;
            gap: 10px;
            border: 2px solid transparent;
        }

        .menu a svg {
            width: 16px;
            height: 16px;
            stroke: currentColor;
            fill: none;
            stroke-width: 2;
        }

        .menu a.active {
            background: var(--gold);
            border-color: var(--line);
            color: #16386d;
            box-shadow: 3px 3px 0 0 #233f74;
        }

        .logout {
            margin-top: 16px;
            text-decoration: none;
            color: #ea3640;
            border: 2px solid #f0b2b5;
            border-radius: 13px;
            font-size: 14px;
            font-weight: 700;
            padding: 10px 14px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .content {
            padding: 24px 24px 20px;
        }

        .page-title {
            margin: 0;
            color: var(--ink);
            font-size: 42px;
            line-height: 1.04;
            font-weight: 800;
        }

        .page-subtitle {
            margin: 8px 0 18px;
            color: #677a9a;
            font-size: 29px;
            font-weight: 500;
        }

        .cards {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 16px;
            align-items: start;
        }

        .review-card {
            background: #fff;
            border: 2px solid #e2e6ee;
            border-radius: 14px;
            padding: 18px 20px;
            min-height: 188px;
            box-shadow: 0 1px 4px rgba(15, 39, 80, .05);
        }

        .review-card.focus {
            border-color: #224381;
            box-shadow: 7px 7px 0 0 #1f3f79;
        }

        .card-head {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 10px;
        }

        .identity {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            border: 1.5px solid #4e6ba0;
            background: #f0dcbc;
            display: grid;
            place-items: center;
            font-size: 16px;
            color: #15396f;
            font-weight: 700;
        }

        .name {
            margin: 0;
            font-size: 32px;
            color: #163a70;
            font-weight: 800;
            line-height: 1.1;
        }

        .name.warn {
            color: #f0b85f;
        }

        .date {
            margin-top: 2px;
            color: #7d8faa;
            font-size: 12px;
            font-weight: 500;
        }

        .score {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            border: 1.5px solid #3d5f94;
            color: #17396f;
            background: #f6c46d;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 700;
            padding: 3px 8px;
            line-height: 1;
        }

        .score svg {
            width: 12px;
            height: 12px;
            fill: #17396f;
            stroke: #17396f;
            stroke-width: 1;
        }

        .event-name {
            margin: 0;
            color: #4f6f97;
            font-size: 11px;
            letter-spacing: .9px;
            font-weight: 800;
            text-transform: uppercase;
        }

        .quote {
            margin: 4px 0 12px;
            font-size: 12px;
            font-style: italic;
            color: #5c7397;
            line-height: 1.5;
        }

        .card-foot {
            border-top: 1px solid #eaedf3;
            padding-top: 10px;
            text-align: right;
        }

        .read-link {
            color: #17396f;
            font-size: 12px;
            font-weight: 800;
            letter-spacing: .7px;
            text-transform: uppercase;
            text-decoration: underline;
        }

        @media (max-width: 1300px) {
            .cards {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 1060px) {
            .layout { grid-template-columns: 1fr; }
            .sidebar {
                flex-direction: row;
                align-items: center;
                gap: 14px;
                border-right: 0;
                border-bottom: 1px solid #e3e5ef;
                padding: 14px;
            }
            .sidebar .top,
            .sidebar .bottom { display: contents; }
            .menu-title,
            .logout { display: none; }
            .menu { display: flex; flex-wrap: wrap; }
            .cards { grid-template-columns: 1fr; }
            .page-title { font-size: 28px; }
            .page-subtitle { font-size: 14px; }
        }
    </style>
</head>
<body>
    <div class="layout">
        <aside class="sidebar">
            <div class="top">
                <div class="brand">NU Lipa EMS<br><small>Admin</small></div>
                <p class="menu-title">ADMIN MENU</p>

                <nav class="menu" aria-label="Admin menu">
                    <a href="{{ route('admin.dashboard') }}">
                        <svg viewBox="0 0 24 24"><rect x="4" y="4" width="6" height="6" rx="1"></rect><rect x="14" y="4" width="6" height="6" rx="1"></rect><rect x="4" y="14" width="6" height="6" rx="1"></rect><rect x="14" y="14" width="6" height="6" rx="1"></rect></svg>
                        Dashboard
                    </a>
                    <a href="{{ route('admin.events') }}">
                        <svg viewBox="0 0 24 24"><rect x="3" y="5" width="18" height="16" rx="2"></rect><line x1="8" y1="3" x2="8" y2="7"></line><line x1="16" y1="3" x2="16" y2="7"></line></svg>
                        Events
                    </a>
                    <a href="{{ route('admin.participants') }}">
                        <svg viewBox="0 0 24 24"><circle cx="12" cy="8" r="4"></circle><path d="M4 20c1.5-3.5 4.5-5 8-5s6.5 1.5 8 5"></path></svg>
                        Participants
                    </a>
                    <a href="{{ route('admin.evaluations') }}" class="active">
                        <svg viewBox="0 0 24 24"><path d="M4 4h16v12H7l-3 4z"></path></svg>
                        Evaluations
                    </a>
                </nav>
            </div>

            <div class="bottom">
                <a href="{{ route('admin.login') }}" class="logout">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                    LOGOUT
                </a>
            </div>
        </aside>

        <main class="content">
            <h1 class="page-title">Event Evaluations</h1>
            <p class="page-subtitle">Review feedback and comments from event participants.</p>

            <section class="cards" aria-label="Evaluation cards">
                <article class="review-card">
                    <div class="card-head">
                        <div class="identity">
                            <span class="avatar">M</span>
                            <div>
                                <p class="name">Maria Santos</p>
                                <p class="date">2026-04-21</p>
                            </div>
                        </div>
                        <span class="score">
                            <svg viewBox="0 0 24 24"><path d="M12 2.5l2.8 5.6 6.2.9-4.5 4.4 1.1 6.1L12 16.8 6.4 19.5l1.1-6.1L3 9l6.2-.9L12 2.5z"></path></svg>
                            5
                        </span>
                    </div>

                    <p class="event-name">NU LIPA FOUNDATION DAY 2026</p>
                    <p class="quote">"Great event! Very organized and the activities were fun for everyone."</p>

                    <div class="card-foot">
                        <a href="#" class="read-link">Read Full Review →</a>
                    </div>
                </article>

                <article class="review-card">
                    <div class="card-head">
                        <div class="identity">
                            <span class="avatar">J</span>
                            <div>
                                <p class="name">John Doe</p>
                                <p class="date">2026-05-16</p>
                            </div>
                        </div>
                        <span class="score">
                            <svg viewBox="0 0 24 24"><path d="M12 2.5l2.8 5.6 6.2.9-4.5 4.4 1.1 6.1L12 16.8 6.4 19.5l1.1-6.1L3 9l6.2-.9L12 2.5z"></path></svg>
                            4
                        </span>
                    </div>

                    <p class="event-name">TECH INNOVATIONS SUMMIT</p>
                    <p class="quote">"Good speakers, but the food was late. Overall, I learned a lot about the new tech trends."</p>

                    <div class="card-foot">
                        <a href="#" class="read-link">Read Full Review →</a>
                    </div>
                </article>

                <article class="review-card">
                    <div class="card-head">
                        <div class="identity">
                            <span class="avatar">A</span>
                            <div>
                                <p class="name">Anna Reyes</p>
                                <p class="date">2026-04-21</p>
                            </div>
                        </div>
                        <span class="score">
                            <svg viewBox="0 0 24 24"><path d="M12 2.5l2.8 5.6 6.2.9-4.5 4.4 1.1 6.1L12 16.8 6.4 19.5l1.1-6.1L3 9l6.2-.9L12 2.5z"></path></svg>
                            5
                        </span>
                    </div>

                    <p class="event-name">NU LIPA FOUNDATION DAY 2026</p>
                    <p class="quote">"Loved the activities. The foundation day was a huge success!"</p>

                    <div class="card-foot">
                        <a href="#" class="read-link">Read Full Review →</a>
                    </div>
                </article>

                <article class="review-card focus">
                    <div class="card-head">
                        <div class="identity">
                            <span class="avatar">M</span>
                            <div>
                                <p class="name warn">Mark Cruz</p>
                                <p class="date">2026-09-11</p>
                            </div>
                        </div>
                        <span class="score">
                            <svg viewBox="0 0 24 24"><path d="M12 2.5l2.8 5.6 6.2.9-4.5 4.4 1.1 6.1L12 16.8 6.4 19.5l1.1-6.1L3 9l6.2-.9L12 2.5z"></path></svg>
                            3
                        </span>
                    </div>

                    <p class="event-name">IT WEEK 2026</p>
                    <p class="quote">"It was okay. The venue was a bit crowded and hot."</p>

                    <div class="card-foot">
                        <a href="#" class="read-link">Read Full Review →</a>
                    </div>
                </article>
            </section>
        </main>
    </div>
</body>
</html>
