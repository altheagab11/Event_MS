@php
$months = [
    ['value' => 0, 'label' => 'Jan'], ['value' => 1, 'label' => 'Feb'], ['value' => 2, 'label' => 'Mar'],
    ['value' => 3, 'label' => 'Apr'], ['value' => 4, 'label' => 'May'], ['value' => 5, 'label' => 'Jun'],
    ['value' => 6, 'label' => 'Jul'], ['value' => 7, 'label' => 'Aug'], ['value' => 8, 'label' => 'Sep'],
    ['value' => 9, 'label' => 'Oct'], ['value' => 10, 'label' => 'Nov'], ['value' => 11, 'label' => 'Dec'],
];

$events = [
    [
        'id' => 1,
        'title' => 'NU Lipa Foundation Day 2026',
        'type' => 'School Event',
        'date' => 'April 20, 2026',
        'location' => 'Main Campus Grounds',
        'description' => 'Join us as we celebrate another year of excellence and commitment to nation-building with booths, games, and performances. This event commemorates the founding of our beloved institution. Students from all colleges are invited to participate in various activities including academic exhibits, cultural presentations, and sports tournaments. The day will conclude with a grand concert featuring local bands and our very own student performers. Attendance is mandatory for all freshmen and sophomores. Please register early to secure your food stub and event kit.',
        'status' => 'active',
        'image' => 'https://images.unsplash.com/photo-1765474604988-4fc3fa14f46b?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxzdHVkZW50cyUyMHNtaWxpbmclMjBjYW1wdXN8ZW58MXx8fHwxNzc1NTM4NjMzfDA&ixlib=rb-4.1.0&q=80&w=1080',
    ],
    [
        'id' => 2,
        'title' => 'Tech Innovations Summit',
        'type' => 'Conference Event',
        'date' => 'May 15, 2026',
        'location' => 'Auditorium Hall B',
        'description' => 'A national conference gathering students and professionals to discuss the latest trends in software and AI. Call for papers is open. This three-day summit will feature keynote speakers from leading tech companies, hands-on workshops on emerging technologies, and a research paper presentation track. We invite scholars, researchers, and students to submit their 5-page research papers on topics including Artificial Intelligence, Cyber Security, Data Science, and Internet of Things. Accepted papers will be published in the conference proceedings. All participants will receive a certificate of participation and a digital ID for venue access.',
        'status' => 'active',
        'image' => 'https://images.unsplash.com/photo-1582192904915-d89c7250b235?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxjb25mZXJlbmNlJTIwdGVjaCUyMHByZXNlbnRhdGlvbnxlbnwxfHx8fDE3NzU1Mzg2MzN8MA&ixlib=rb-4.1.0&q=80&w=1080',
    ],
    [
        'id' => 4,
        'title' => 'University Intramurals 2026',
        'type' => 'School Event',
        'date' => 'June 5-8, 2026',
        'location' => 'University Gymnasium & Field',
        'description' => 'Show your college pride! The annual University Intramurals is back with exciting basketball, volleyball, e-sports, and cheering competitions. Form your teams and register now.',
        'status' => 'active',
        'image' => 'https://images.unsplash.com/photo-1721339040530-be9c4eff0360?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHx1bml2ZXJzaXR5JTIwc3BvcnRzJTIwYmFza2V0YmFsbCUyMGludHJhbXVyYWxzfGVufDF8fHx8MTc3NTU0MjgxOHww&ixlib=rb-4.1.0&q=80&w=1080&utm_source=figma&utm_medium=referral',
    ],
    [
        'id' => 5,
        'title' => 'Sining at Kultura Festival',
        'type' => 'School Event',
        'date' => 'August 12, 2026',
        'location' => 'Open Amphitheater',
        'description' => 'A celebration of Filipino heritage through traditional dances, spoken word poetry, and visual arts exhibitions. Join us in preserving and promoting our rich culture.',
        'status' => 'active',
        'image' => 'https://images.unsplash.com/photo-1709086566111-dfa0699a6842?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxmaWxpcGlubyUyMHN0dWRlbnRzJTIwY3VsdHVyYWwlMjBkYW5jaW5nfGVufDF8fHx8MTc3NTU0MjgxOHww&ixlib=rb-4.1.0&q=80&w=1080&utm_source=figma&utm_medium=referral',
    ],
    [
        'id' => 6,
        'title' => 'Medical & Allied Health Research Symposium',
        'type' => 'Conference Event',
        'date' => 'September 22, 2026',
        'location' => 'College of Allied Health Sciences Bldg',
        'description' => 'Presenting breakthrough researches in the field of Nursing, Pharmacy, and Medical Technology. Open for professional and student researchers nationwide.',
        'status' => 'active',
        'image' => 'https://images.unsplash.com/photo-1773828755374-0ee802d9f44b?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxtZWRpY2FsJTIwaGVhbHRoJTIwc3ltcG9zaXVtJTIwcmVzZWFyY2h8ZW58MXx8fHwxNzc1NTQyODE5fDA&ixlib=rb-4.1.0&q=80&w=1080&utm_source=figma&utm_medium=referral',
    ],
    [
        'id' => 3,
        'title' => 'Leadership Seminar Series',
        'type' => 'School Event',
        'date' => 'March 10, 2026',
        'location' => 'Student Lounge',
        'description' => 'Empowering the next generation of leaders. (Event concluded)',
        'status' => 'ended',
        'image' => 'https://images.unsplash.com/photo-1664273891579-22f28332f3c4?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHx1bml2ZXJzaXR5JTIwY2FtcHVzJTIwbW9kZXJufGVufDF8fHx8MTc3NTUzODYzM3ww&ixlib=rb-4.1.0&q=80&w=1080',
    ],
];

$announcements = [
    [
        'id' => 1,
        'title' => 'Call for Papers: Tech Innovations Summit',
        'description' => 'Call for Papers for the Tech Innovations Summit is now open! Register and submit your PDF manuscripts until May 1, 2026.',
        'type' => 'important',
        'eventId' => 2,
        'buttonText' => 'Register & Submit Paper'
    ],
    [
        'id' => 2,
        'title' => 'Intramurals 2026 Team Registration',
        'description' => 'Team registrations for Basketball and Volleyball are now ongoing at the Student Council Office.',
        'type' => 'info',
        'eventId' => 4,
        'buttonText' => 'View Intramurals'
    ],
    [
        'id' => 3,
        'title' => 'Foundation Day Reminders',
        'description' => 'Attendance is mandatory for all freshmen and sophomores. Please register early to secure your event kit.',
        'type' => 'info',
        'eventId' => 1,
        'buttonText' => 'Register Now'
    ],
];

$regions = [
    'NCR (National Capital Region)', 'CAR (Cordillera Administrative Region)', 'Region I (Ilocos Region)', 'Region II (Cagayan Valley)',
    'Region III (Central Luzon)', 'Region IV-A (CALABARZON)', 'MIMAROPA Region', 'Region V (Bicol Region)', 'Region VI (Western Visayas)',
    'Region VII (Central Visayas)', 'Region VIII (Eastern Visayas)', 'Region IX (Zamboanga Peninsula)', 'Region X (Northern Mindanao)',
    'Region XI (Davao Region)', 'Region XII (SOCCSKSARGEN)', 'Region XIII (Caraga)', 'BARMM (Bangsamoro Autonomous Region in Muslim Mindanao)'
];
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>NU Lipa EMS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="{{ asset('css/landingpage.css') }}">
</head>
<body class="landing-page">
    <div class="landing-scroll">
    <div class="landing-scroll-content">
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
                <button class="pill pill-muted">Home / Events</button>
                <a href="{{ route('admin.login') }}" class="pill pill-gold admin-btn" style="text-decoration:none;">
                    <span class="pill-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" role="img" focusable="false">
                            <path d="M12 3l7 3v5c0 4.8-3 8.6-7 10-4-1.4-7-5.2-7-10V6l7-3z"></path>
                            <path d="M9.5 12l2 2 3.5-3.5"></path>
                        </svg>
                    </span>
                    <span>Admin Login</span>
                </a>
            </div>
        </div>
    </header>

    <section class="hero">
        <div class="container hero-inner">
            <h1>Welcome to <span>NU Lipa Events</span></h1>
            <p>The unified platform for typical school events and research conferences. Register, participate, and evaluate all in one place.</p>
            <a href="#events" class="cta">
                <span>View Upcoming Events</span>
                <span class="cta-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" role="img" focusable="false">
                        <line x1="4" y1="12" x2="20" y2="12"></line>
                        <polyline points="13,5 20,12 13,19"></polyline>
                    </svg>
                </span>
            </a>
        </div>
    </section>

    <section class="announce">
        <div class="container">
            <h2 class="announce-title">
                <span class="announce-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" role="img" focusable="false">
                        <path d="M10 5a2 2 0 1 1 4 0a7 7 0 0 1 4 6v3l2 2H4l2-2v-3a7 7 0 0 1 4-6"></path>
                        <path d="M9 17v1a3 3 0 0 0 6 0v-1"></path>
                    </svg>
                </span>
                <span>Latest Announcements</span>
            </h2>
            <div class="announce-grid">
                @foreach ($announcements as $a)
                    <div class="announce-card {{ $a['type'] === 'important' ? 'important' : '' }}">
                        <div>
                            <h3 style="color: {{ $a['type'] === 'important' ? '#ffc570' : '#fff' }}">{{ $a['title'] }}</h3>
                            <p>{{ $a['description'] }}</p>
                        </div>
                        <button class="announce-btn {{ str_contains($a['buttonText'], 'Register') ? 'gold' : 'ghost' }} open-register" data-event-id="{{ $a['eventId'] }}">{{ $a['buttonText'] }}</button>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section id="events" class="events">
        <div class="container">
            <div class="events-head">
                <h2>Discover Events</h2>
                <p>Browse through school gatherings and major conferences.</p>
            </div>

            <div class="filters">
                <div class="filter-group">
                    <button class="fbtn active" data-category="All">
                        <span class="filter-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" role="img" focusable="false">
                                <rect x="3.5" y="5.5" width="17" height="15" rx="2.5"></rect>
                                <line x1="3.5" y1="9" x2="20.5" y2="9"></line>
                                <line x1="8" y1="3.5" x2="8" y2="7"></line>
                                <line x1="16" y1="3.5" x2="16" y2="7"></line>
                            </svg>
                        </span>
                        <span>All</span>
                    </button>
                    <button class="fbtn" data-category="School Event">
                        <span class="filter-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" role="img" focusable="false">
                                <path d="M3 9l9-4 9 4-9 4-9-4z"></path>
                                <path d="M6.5 10.6V14c0 2.1 2.6 3.8 5.5 3.8s5.5-1.7 5.5-3.8v-3.4"></path>
                            </svg>
                        </span>
                        <span>School</span>
                    </button>
                    <button class="fbtn" data-category="Conference Event">
                        <span class="filter-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" role="img" focusable="false">
                                <rect x="4" y="7" width="16" height="12" rx="2"></rect>
                                <line x1="9" y1="7" x2="9" y2="19"></line>
                                <path d="M10.8 7V5.8a1.8 1.8 0 0 1 1.8-1.8h.8a1.8 1.8 0 0 1 1.8 1.8V7"></path>
                            </svg>
                        </span>
                        <span>Conference</span>
                    </button>
                </div>
                <button class="fmonth" id="toggleDateFilter">
                    <span class="month-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" role="img" focusable="false">
                            <rect x="3.5" y="5.5" width="17" height="15" rx="2.5"></rect>
                            <line x1="3.5" y1="9" x2="20.5" y2="9"></line>
                            <line x1="8" y1="3.5" x2="8" y2="7"></line>
                            <line x1="16" y1="3.5" x2="16" y2="7"></line>
                        </svg>
                    </span>
                    <span>Filter by Month</span>
                </button>

                <div class="date-pop" id="dateFilterPop">
                    <label for="yearInput">Year</label>
                    <input id="yearInput" type="number" placeholder="e.g. 2026">
                    <label>Month</label>
                    <div class="month-grid" id="monthBtns">
                        <button class="mbtn month-all active" data-month="All">All Months</button>
                        @foreach($months as $m)
                            <button class="mbtn" data-month="{{ $m['value'] }}">{{ $m['label'] }}</button>
                        @endforeach
                    </div>
                    <div class="dp-actions">
                        <button class="clear" id="clearDateFilter">Clear</button>
                        <button class="apply" id="applyDateFilter">Apply</button>
                    </div>
                </div>
            </div>

            <div class="events-grid" id="eventsGrid">
                @foreach ($events as $event)
                    @php
                        preg_match('/([a-zA-Z]+).*\s(\d{4})/', $event['date'], $match);
                        $monthIndex = isset($match[1]) ? date('n', strtotime($match[1] . ' 1, 2000')) - 1 : -1;
                        $year = $match[2] ?? '';
                    @endphp
                    <article class="event-card" data-id="{{ $event['id'] }}" data-type="{{ $event['type'] }}" data-month="{{ $monthIndex }}" data-year="{{ $year }}">
                        <div class="event-media">
                            <img src="{{ $event['image'] }}" alt="{{ $event['title'] }}">
                            <span class="badge {{ $event['type'] === 'Conference Event' ? 'conference' : '' }}">{{ $event['type'] }}</span>
                        </div>
                        <div class="event-body">
                            <h3 class="event-title">{{ $event['title'] }}</h3>
                            <p class="meta">
                                <span class="meta-row">
                                    <span class="meta-icon" aria-hidden="true">
                                        <svg viewBox="0 0 24 24" role="img" focusable="false">
                                            <rect x="4.5" y="5.8" width="15" height="13.7" rx="2.2"></rect>
                                            <line x1="4.5" y1="9" x2="19.5" y2="9"></line>
                                            <line x1="8" y1="3.8" x2="8" y2="7"></line>
                                            <line x1="16" y1="3.8" x2="16" y2="7"></line>
                                        </svg>
                                    </span>
                                    <span>{{ $event['date'] }}</span>
                                </span>
                                <span class="meta-row">
                                    <span class="meta-icon" aria-hidden="true">
                                        <svg viewBox="0 0 24 24" role="img" focusable="false">
                                            <path d="M12 20s-5.2-4.6-5.2-8.9A5.2 5.2 0 1 1 17.2 11c0 4.3-5.2 9-5.2 9Z"></path>
                                            <circle cx="12" cy="11" r="1.9"></circle>
                                        </svg>
                                    </span>
                                    <span>{{ $event['location'] }}</span>
                                </span>
                            </p>
                            <p class="desc">{{ $event['description'] }}</p>
                            <div class="event-actions">
                                @if ($event['status'] === 'active')
                                    <button class="event-btn open-register" data-event-id="{{ $event['id'] }}">Register Now</button>
                                @else
                                    <button class="event-btn outline open-evaluate" data-event-id="{{ $event['id'] }}">
                                        <span class="eval-icon" aria-hidden="true">
                                            <svg viewBox="0 0 24 24" role="img" focusable="false">
                                                <polygon points="12,4.5 14.4,9.4 19.8,10.2 15.9,14 16.8,19.4 12,16.9 7.2,19.4 8.1,14 4.2,10.2 9.6,9.4"></polygon>
                                            </svg>
                                        </span>
                                        <span>Evaluate Event</span>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

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
    </div>

    <div class="page-blur" id="pageBlur" aria-hidden="true"></div>

    <div class="modal" id="eventModal">
        <div class="modal-card" id="modalCard">
            <button type="button" class="close-modal" id="closeModal" aria-label="Close modal">×</button>
            <div id="modalContent"></div>
        </div>
    </div>

    <script>
        (function setupLandingScrollCompensation() {
            const isWindows = navigator.userAgent.includes('Windows');
            const landingScroll = document.querySelector('.landing-scroll');
            const landingContent = document.querySelector('.landing-scroll-content');

            if (!isWindows || !landingScroll || !landingContent) return;

            const applyCompensation = () => {
                const scrollbarWidth = Math.max(landingScroll.offsetWidth - landingScroll.clientWidth, 0);
                const shift = scrollbarWidth > 0 ? (scrollbarWidth / 2) : 0;
                landingContent.style.setProperty('--landing-scroll-shift', `${shift}px`);
            };

            applyCompensation();
            window.addEventListener('resize', applyCompensation);
        })();

        const EVENTS = @json($events);
        const REGIONS = @json($regions);

        let filterCategory = 'All';
        let filterMonth = 'All';
        let filterYear = '';

        const dateFilterPop = document.getElementById('dateFilterPop');
        document.getElementById('toggleDateFilter').addEventListener('click', () => {
            dateFilterPop.classList.toggle('open');
        });

        document.querySelectorAll('.fbtn').forEach(btn => {
            btn.addEventListener('click', () => {
                document.querySelectorAll('.fbtn').forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                filterCategory = btn.dataset.category;
                applyFilters();
            });
        });

        document.querySelectorAll('.mbtn').forEach(btn => {
            btn.addEventListener('click', () => {
                document.querySelectorAll('.mbtn').forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                filterMonth = btn.dataset.month;
            });
        });

        document.getElementById('clearDateFilter').addEventListener('click', () => {
            filterMonth = 'All';
            filterYear = '';
            document.getElementById('yearInput').value = '';
            document.querySelectorAll('.mbtn').forEach(b => b.classList.remove('active'));
            document.querySelector('.month-all').classList.add('active');
            applyFilters();
            dateFilterPop.classList.remove('open');
        });

        document.getElementById('applyDateFilter').addEventListener('click', () => {
            filterYear = document.getElementById('yearInput').value.trim();
            applyFilters();
            dateFilterPop.classList.remove('open');
        });

        function applyFilters() {
            document.querySelectorAll('.event-card').forEach(card => {
                const matchesCategory = filterCategory === 'All' || card.dataset.type === filterCategory;
                const matchesMonth = filterMonth === 'All' || card.dataset.month === String(filterMonth);
                const matchesYear = !filterYear || card.dataset.year === filterYear;
                card.style.display = (matchesCategory && matchesMonth && matchesYear) ? '' : 'none';
            });
        }

        const eventModal = document.getElementById('eventModal');
        const modalContent = document.getElementById('modalContent');
        const modalCard = document.getElementById('modalCard');
        const pageBlur = document.getElementById('pageBlur');
        let selectedEvent = null;
        let registrationData = { firstName: '', lastName: '' };
        let otpTimer = null;
        let countdown = 59;

        function closeModal() {
            eventModal.classList.remove('open');
            modalCard.classList.remove('eval-mode');
            modalCard.classList.remove('success-mode');
            document.body.classList.remove('modal-open');
            pageBlur.classList.remove('open');
            modalContent.innerHTML = '';
            selectedEvent = null;
            if (otpTimer) clearInterval(otpTimer);
        }

        function makeOtpInputs() {
            return `<div class="otp-row">${Array.from({ length: 6 }, (_, i) => `<input maxlength="1" data-otp="${i}" required>`).join('')}</div>`;
        }

        function renderFormStep() {
            const showUpload = selectedEvent.type === 'Conference Event';
            modalContent.innerHTML = `
                <div class="event-head">
                    <img src="${selectedEvent.image}" alt="${selectedEvent.title}">
                    <div class="event-overlay">
                        <div>
                            <span class="badge ${selectedEvent.type === 'Conference Event' ? 'conference' : ''}">${selectedEvent.type}</span>
                            <h2>${selectedEvent.title}</h2>
                        </div>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="chips">
                        <div class="chip-item">
                            <span class="chip-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" role="img" focusable="false">
                                    <rect x="4.5" y="5.8" width="15" height="13.7" rx="2.2"></rect>
                                    <line x1="4.5" y1="9" x2="19.5" y2="9"></line>
                                    <line x1="8" y1="3.8" x2="8" y2="7"></line>
                                    <line x1="16" y1="3.8" x2="16" y2="7"></line>
                                </svg>
                            </span>
                            <span>${selectedEvent.date}</span>
                        </div>
                        <div class="chip-item">
                            <span class="chip-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" role="img" focusable="false">
                                    <path d="M12 20s-5.2-4.6-5.2-8.9A5.2 5.2 0 1 1 17.2 11c0 4.3-5.2 9-5.2 9Z"></path>
                                    <circle cx="12" cy="11" r="1.9"></circle>
                                </svg>
                            </span>
                            <span>${selectedEvent.location}</span>
                        </div>
                    </div>
                    <div class="desc-block">${selectedEvent.description}</div>
                    <div class="step-tag">STEP 1 OF 2: REGISTRATION</div>
                    <h3 style="color:#1a3263; margin: 16px 0; font-size: 28px;">Registration Form</h3>
                    <form id="registrationForm">
                        <div class="grid-2">
                            <div class="field"><label>First Name *</label><input name="firstName" required></div>
                            <div class="field"><label>Last Name *</label><input name="lastName" required></div>
                            <div class="field"><label>Email Address *</label><input type="email" name="email" required></div>
                            <div class="field"><label>Region in the Philippines *</label><select name="region" required><option value="">Select Region</option>${REGIONS.map(r => `<option value="${r}">${r}</option>`).join('')}</select></div>
                            <div class="field"><label>School From *</label><input name="schoolFrom" required></div>
                            <div class="field"><label>School Level *</label><select name="schoolLevel" required><option value="">Select Level</option><option>Senior High School</option><option>Undergraduate</option><option>Graduate</option><option>Professional</option></select></div>
                        </div>
                        <div class="upload-wrap" style="display:${showUpload ? 'block' : 'none'}">
                            <div class="upload-title">Upload 5-page Research Paper (PDF only)</div>
                            <div class="upload-note">Your paper will be subject to admin review before your registration is fully confirmed.</div>
                            <label class="upload-drop">
                                <input class="upload-file-input" type="file" accept=".pdf" ${showUpload ? 'required' : ''}>
                                <span class="upload-drop-icon" aria-hidden="true">
                                    <svg viewBox="0 0 24 24" role="img" focusable="false">
                                        <path d="M12 16V6"></path>
                                        <path d="M8.5 9.5 12 6l3.5 3.5"></path>
                                        <path d="M6.5 14.5a4 4 0 0 1 .8-7.9A5.5 5.5 0 0 1 18 7.8a3.6 3.6 0 0 1-.8 6.7"></path>
                                        <path d="M9 16.5h6"></path>
                                    </svg>
                                </span>
                                <span class="upload-drop-main">Click or drag PDF to upload</span>
                                <span class="upload-drop-sub">Maximum file size: 10MB</span>
                            </label>
                        </div>
                        <button class="submit" type="submit">Continue Registration</button>
                    </form>
                </div>
            `;
            document.getElementById('registrationForm').addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(e.target);
                registrationData = {
                    firstName: String(formData.get('firstName') || '').trim(),
                    lastName: String(formData.get('lastName') || '').trim(),
                };
                renderOtpStep();
            });
        }

        function renderOtpStep() {
            countdown = 59;
            modalContent.innerHTML = `
                <div class="modal-body" style="padding:48px; text-align:center;">
                    <div class="step-tag">STEP 2 OF 2: VERIFICATION</div>
                    <h3 style="font-size:34px; color:#1a3263; margin:20px 0 8px;">Verify Your Email</h3>
                    <p style="color:#6b7280; max-width:520px; margin:0 auto 12px;">We've sent a 6-digit alphanumeric code to your email. Please enter it below to verify your identity.</p>
                    <form id="otpForm">
                        ${makeOtpInputs()}
                        <button class="submit" type="submit">Verify & Submit</button>
                    </form>
                    <div style="margin-top:14px; color:#6b7280;" id="countdown">0:59</div>
                </div>
            `;

            const otpInputs = Array.from(document.querySelectorAll('[data-otp]'));
            otpInputs.forEach((input, idx) => {
                input.addEventListener('input', (e) => {
                    e.target.value = e.target.value.slice(-1).toUpperCase();
                    if (e.target.value && idx < otpInputs.length - 1) otpInputs[idx + 1].focus();
                });
                input.addEventListener('keydown', (e) => {
                    if (e.key === 'Backspace' && !e.target.value && idx > 0) otpInputs[idx - 1].focus();
                });
            });

            if (otpTimer) clearInterval(otpTimer);
            otpTimer = setInterval(() => {
                countdown -= 1;
                document.getElementById('countdown').textContent = `0:${String(Math.max(countdown, 0)).padStart(2, '0')}`;
                if (countdown <= 0) clearInterval(otpTimer);
            }, 1000);

            document.getElementById('otpForm').addEventListener('submit', function(e) {
                e.preventDefault();
                renderSuccessStep();
            });
        }

        function renderSuccessStep() {
            const isConference = selectedEvent.type === 'Conference Event';
            const code = 'NUL-' + Math.random().toString(36).substring(2, 8).toUpperCase();
            const fullName = `${registrationData.firstName} ${registrationData.lastName}`.trim() || 'Student Name';
            modalCard.classList.add('success-mode');
            modalContent.innerHTML = `
                <div class="registration-success">
                    <span class="success-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" role="img" focusable="false">
                            <circle cx="12" cy="12" r="9"></circle>
                            <path d="M8.5 12.3 10.8 14.6 15.8 9.6"></path>
                        </svg>
                    </span>
                        <h3>${isConference ? 'Registration Submitted!' : 'Verification Successful!'}</h3>
                    <p class="success-copy">
                        ${isConference
                            ? 'Your registration and research paper are now pending review. Once approved by the admin, you will receive an email containing your Digital ID.'
                            : 'Your Digital ID has been successfully sent to your email. Here is a preview of what you will receive:'}
                    </p>

                    <div class="success-grid">
                        <div class="pass-card">
                            <div class="pass-top">
                                <div class="pass-kicker">NU LIPA EVENT PASS</div>
                                <span class="pass-tag">SENIOR HIGH</span>
                            </div>
                            <div class="pass-event">${selectedEvent.title}</div>
                            <div class="pass-name">${fullName}</div>
                            <div class="pass-meta">
                                <div class="pass-meta-row">
                                    <span class="pass-meta-icon" aria-hidden="true">
                                        <svg viewBox="0 0 24 24" role="img" focusable="false">
                                            <path d="M4 19V5"></path>
                                            <path d="M4 6h9"></path>
                                            <path d="M7 9h6"></path>
                                            <path d="M7 13h4"></path>
                                        </svg>
                                    </span>
                                    <span>${selectedEvent.type}</span>
                                </div>
                                <div class="pass-meta-row">
                                    <span class="pass-meta-icon" aria-hidden="true">
                                        <svg viewBox="0 0 24 24" role="img" focusable="false">
                                            <path d="M12 20s-5.2-4.6-5.2-8.9A5.2 5.2 0 1 1 17.2 11c0 4.3-5.2 9-5.2 9Z"></path>
                                            <circle cx="12" cy="11" r="1.9"></circle>
                                        </svg>
                                    </span>
                                    <span>${selectedEvent.location}</span>
                                </div>
                            </div>
                            <div class="pass-divider"></div>
                            <div class="pass-footer">
                                <label>Attendee ID</label>
                                <span class="pass-code">${code}</span>
                            </div>
                        </div>

                        <div class="qr-card">
                            <div class="qr-top">SCAN AT REGISTRATION</div>
                            <div class="qr-body">
                                <div class="qr-box" aria-hidden="true">
                                    <svg viewBox="0 0 24 24" role="img" focusable="false">
                                        <rect x="4" y="4" width="7" height="7" rx="1.2"></rect>
                                        <rect x="13" y="4" width="3" height="3" rx=".8"></rect>
                                        <rect x="18" y="4" width="2" height="2" rx=".5"></rect>
                                        <rect x="4" y="13" width="4" height="4" rx="1"></rect>
                                        <rect x="11" y="11" width="3" height="3" rx=".7"></rect>
                                        <path d="M16 12h2"></path>
                                        <path d="M18 12v2"></path>
                                        <path d="M15 17h3"></path>
                                        <path d="M15 20v-3"></path>
                                        <path d="M20 18h-2"></path>
                                        <path d="M20 20v-2"></path>
                                    </svg>
                                </div>
                                <p class="qr-note">This QR code is strictly non-transferable and must be presented during event check-in.</p>
                            </div>
                        </div>
                    </div>

                    <div class="success-actions">
                        <button type="button" class="success-action-btn">
                            <span class="success-action-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" role="img" focusable="false">
                                    <path d="M12 3v10"></path>
                                    <path d="M8.5 9.5 12 13l3.5-3.5"></path>
                                    <path d="M5 16.5v2.5h14v-2.5"></path>
                                </svg>
                            </span>
                            <span>Download Pass</span>
                        </button>
                        <button type="button" class="success-action-btn success-action-secondary">
                            <span class="success-action-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" role="img" focusable="false">
                                    <path d="M14 4h6v6"></path>
                                    <path d="M10 14 20 4"></path>
                                    <path d="M20 14v6h-6"></path>
                                    <path d="M4 10v6a4 4 0 0 0 4 4h6"></path>
                                </svg>
                            </span>
                            <span>Share to Email</span>
                        </button>
                    </div>
                    <button type="button" class="success-done" id="doneBtn">Done</button>
                </div>
            `;
            document.getElementById('doneBtn').addEventListener('click', closeModal);
        }

        function openRegister(eventId) {
            selectedEvent = EVENTS.find(e => e.id === Number(eventId));
            if (!selectedEvent) return;
            modalCard.classList.remove('eval-mode');
            eventModal.classList.add('open');
            document.body.classList.add('modal-open');
            pageBlur.classList.add('open');
            renderFormStep();
        }

        function openEvaluate(eventId) {
            selectedEvent = EVENTS.find(e => e.id === Number(eventId));
            if (!selectedEvent) return;
            modalCard.classList.add('eval-mode');
            eventModal.classList.add('open');
            document.body.classList.add('modal-open');
            pageBlur.classList.add('open');
            let rating = 0;
            let hoveredRating = 0;
            let evalSubmitted = false;

            function renderEvaluateState() {
                if (evalSubmitted) {
                    modalContent.innerHTML = `
                        <div class="eval-modal">
                            <div class="eval-head">
                                <h3>
                                    <span class="eval-head-icon" aria-hidden="true">
                                        <svg viewBox="0 0 24 24" role="img" focusable="false">
                                            <polygon points="12,4.5 14.4,9.4 19.8,10.2 15.9,14 16.8,19.4 12,16.9 7.2,19.4 8.1,14 4.2,10.2 9.6,9.4"></polygon>
                                        </svg>
                                    </span>
                                    Evaluate Event
                                </h3>
                            </div>
                            <div class="eval-body eval-success">
                                <span class="eval-success-icon" aria-hidden="true">
                                    <svg viewBox="0 0 24 24" role="img" focusable="false">
                                        <circle cx="12" cy="12" r="9"></circle>
                                        <path d="M8.5 12.3 10.8 14.6 15.8 9.6"></path>
                                    </svg>
                                </span>
                                <h4>Thank You!</h4>
                                <p>Your feedback helps us improve future events.</p>
                                <button type="button" class="eval-close-cta" id="doneBtn">Close</button>
                            </div>
                        </div>
                    `;
                    document.getElementById('doneBtn').addEventListener('click', closeModal);
                    return;
                }

                modalContent.innerHTML = `
                    <div class="eval-modal">
                        <div class="eval-head">
                            <h3>
                                <span class="eval-head-icon" aria-hidden="true">
                                    <svg viewBox="0 0 24 24" role="img" focusable="false">
                                        <polygon points="12,4.5 14.4,9.4 19.8,10.2 15.9,14 16.8,19.4 12,16.9 7.2,19.4 8.1,14 4.2,10.2 9.6,9.4"></polygon>
                                    </svg>
                                </span>
                                Evaluate Event
                            </h3>
                        </div>
                        <div class="eval-body">
                            <p class="eval-copy">Please rate your experience at ${selectedEvent.title}.</p>
                            <div class="eval-stars" role="radiogroup" aria-label="Rate event">
                                ${Array.from({ length: 5 }, (_, i) => `
                                    <button type="button" class="rating-star" data-rate="${i + 1}" aria-label="${i + 1} star">
                                        <svg viewBox="0 0 24 24" role="img" focusable="false">
                                            <polygon points="12,4.5 14.4,9.4 19.8,10.2 15.9,14 16.8,19.4 12,16.9 7.2,19.4 8.1,14 4.2,10.2 9.6,9.4"></polygon>
                                        </svg>
                                    </button>
                                `).join('')}
                            </div>
                            <label class="eval-label">ADDITIONAL COMMENTS (OPTIONAL)</label>
                            <textarea class="eval-text" rows="4" placeholder="What did you like? What can we improve?"></textarea>
                            <button type="button" class="eval-submit" id="evalSubmitBtn" ${rating === 0 ? 'disabled' : ''}>Submit Evaluation</button>
                        </div>
                    </div>
                `;

                const stars = Array.from(document.querySelectorAll('.rating-star'));
                const submitBtn = document.getElementById('evalSubmitBtn');

                function paintStars(activeValue) {
                    stars.forEach((star, idx) => {
                        star.classList.toggle('active', idx < activeValue);
                    });
                }

                paintStars(hoveredRating || rating);

                stars.forEach((star, idx) => {
                    star.addEventListener('mouseenter', () => {
                        hoveredRating = idx + 1;
                        paintStars(hoveredRating);
                    });
                    star.addEventListener('mouseleave', () => {
                        hoveredRating = 0;
                        paintStars(rating);
                    });
                    star.addEventListener('click', () => {
                        rating = idx + 1;
                        paintStars(rating);
                        submitBtn.disabled = rating === 0;
                    });
                });

                submitBtn.addEventListener('click', () => {
                    if (rating === 0) return;
                    evalSubmitted = true;
                    renderEvaluateState();
                });
            }

            renderEvaluateState();
        }

        document.querySelectorAll('.open-register').forEach(btn => {
            btn.addEventListener('click', () => openRegister(btn.dataset.eventId));
        });
        document.querySelectorAll('.open-evaluate').forEach(btn => {
            btn.addEventListener('click', () => openEvaluate(btn.dataset.eventId));
        });
        document.getElementById('closeModal').addEventListener('click', closeModal);
        eventModal.addEventListener('click', (e) => {
            if (e.target === eventModal) closeModal();
        });
    </script>
</body>
</html>

