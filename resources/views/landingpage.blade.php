@php
$months = [
['value' => 0, 'label' => 'Jan'], ['value' => 1, 'label' => 'Feb'], ['value' => 2, 'label' => 'Mar'],
['value' => 3, 'label' => 'Apr'], ['value' => 4, 'label' => 'May'], ['value' => 5, 'label' => 'Jun'],
['value' => 6, 'label' => 'Jul'], ['value' => 7, 'label' => 'Aug'], ['value' => 8, 'label' => 'Sep'],
['value' => 9, 'label' => 'Oct'], ['value' => 10, 'label' => 'Nov'], ['value' => 11, 'label' => 'Dec'],
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
  <meta name="csrf-token" content="{{ csrf_token() }}">
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
            @forelse ($events as $event)
            <article class="event-card" data-id="{{ $event['id'] }}" data-type="{{ $event['type'] }}" data-month="{{ $event['month'] }}" data-year="{{ $event['year'] }}">
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
            @empty
            <article class="event-card" style="grid-column: 1 / -1;">
              <div class="event-body">
                <h3 class="event-title">No events available</h3>
                <p class="desc">Admin has not published any events yet.</p>
              </div>
            </article>
            @endforelse
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
    const REGISTER_SEND_URL = @json(route('registration.send-verification'));
    const REGISTER_VERIFY_URL = @json(route('registration.verify-code'));
    const CSRF_TOKEN = @json(csrf_token());

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
    let registrationData = {
      firstName: '',
      lastName: '',
      email: '',
      region: '',
      schoolFrom: '',
      schoolLevel: '',
      verificationId: null,
      registrationStatus: '',
      passCode: '',
      registrationId: null,
      mailSent: false,
    };
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

    function escapeHtml(value) {
      return String(value ?? '')
        .replaceAll('&', '&amp;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;')
        .replaceAll('"', '&quot;')
        .replaceAll("'", '&#039;');
    }

    async function postForm(url, formData) {
      const response = await fetch(url, {
        method: 'POST',
        headers: {
          'Accept': 'application/json',
          'X-CSRF-TOKEN': CSRF_TOKEN,
        },
        body: formData,
      });

      const payload = await response.json().catch(() => ({}));
      if (!response.ok) {
        const validationMessage = payload.errors
          ? Object.values(payload.errors).flat().join(' ')
          : '';
        throw new Error(validationMessage || payload.message || 'Request failed.');
      }

      return payload;
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
                    <div id="registerFormMessage" style="display:none; margin:0 0 12px; padding:10px 12px; border-radius:10px; font-size:13px; font-weight:600;"></div>
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
                            <input class="upload-file-input" type="file" name="paperFile" accept=".pdf" ${showUpload ? 'required' : ''}>
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
                        <button class="submit" type="submit" id="continueRegistrationBtn">Continue Registration</button>
                    </form>
                </div>
            `;
      document.getElementById('registrationForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const formData = new FormData(e.target);
        const message = document.getElementById('registerFormMessage');
        const submitButton = document.getElementById('continueRegistrationBtn');

        const payload = new FormData();
        payload.append('event_id', String(selectedEvent.id));
        payload.append('first_name', String(formData.get('firstName') || '').trim());
        payload.append('last_name', String(formData.get('lastName') || '').trim());
        payload.append('email', String(formData.get('email') || '').trim());
        payload.append('region', String(formData.get('region') || '').trim());
        payload.append('school_from', String(formData.get('schoolFrom') || '').trim());
        payload.append('school_level', String(formData.get('schoolLevel') || '').trim());

        const paperFile = formData.get('paperFile');
        if (paperFile instanceof File && paperFile.size > 0) {
          payload.append('paper_file', paperFile);
        }

        registrationData = {
          firstName: String(formData.get('firstName') || '').trim(),
          lastName: String(formData.get('lastName') || '').trim(),
          email: String(formData.get('email') || '').trim(),
          region: String(formData.get('region') || '').trim(),
          schoolFrom: String(formData.get('schoolFrom') || '').trim(),
          schoolLevel: String(formData.get('schoolLevel') || '').trim(),
          verificationId: null,
          registrationStatus: '',
          passCode: '',
          registrationId: null,
          mailSent: false,
        };

        message.style.display = 'none';
        submitButton.disabled = true;
        submitButton.textContent = 'Sending Verification...';

        try {
          const response = await postForm(REGISTER_SEND_URL, payload);
          registrationData.verificationId = Number(response?.data?.verification_id || 0) || null;
          renderOtpStep(response?.data?.email_masked || registrationData.email);
        } catch (error) {
          message.style.display = 'block';
          message.style.border = '2px solid #f1b4ba';
          message.style.background = '#fff3f4';
          message.style.color = '#8b1e2b';
          message.textContent = error instanceof Error ? error.message : 'Unable to continue registration.';
        } finally {
          submitButton.disabled = false;
          submitButton.textContent = 'Continue Registration';
        }
      });
    }

    function renderOtpStep(maskedEmail) {
      countdown = 59;
      modalContent.innerHTML = `
                <div class="modal-body" style="padding:48px; text-align:center;">
                    <div class="step-tag">STEP 2 OF 2: VERIFICATION</div>
                    <h3 style="font-size:34px; color:#1a3263; margin:20px 0 8px;">Verify Your Email</h3>
            <p style="color:#6b7280; max-width:520px; margin:0 auto 8px;">We've sent a 6-digit alphanumeric code to <strong>${escapeHtml(maskedEmail || registrationData.email)}</strong>. Please enter it below to verify your identity.</p>
            <div id="otpMessage" style="display:none; margin:10px auto 12px; max-width:520px; padding:10px 12px; border-radius:10px; font-size:13px; font-weight:600;"></div>
                    <form id="otpForm">
                        ${makeOtpInputs()}
              <button class="submit" type="submit" id="verifyOtpBtn">Verify & Submit</button>
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

      document.getElementById('otpForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const message = document.getElementById('otpMessage');
        const verifyButton = document.getElementById('verifyOtpBtn');
        const code = otpInputs.map(input => input.value.trim()).join('').toUpperCase();

        if (code.length !== 6) {
          message.style.display = 'block';
          message.style.border = '2px solid #f1b4ba';
          message.style.background = '#fff3f4';
          message.style.color = '#8b1e2b';
          message.textContent = 'Please enter the 6-character verification code.';
          return;
        }

        const payload = new FormData();
        payload.append('verification_id', String(registrationData.verificationId || ''));
        payload.append('code', code);

        message.style.display = 'none';
        verifyButton.disabled = true;
        verifyButton.textContent = 'Verifying...';

        try {
          const response = await postForm(REGISTER_VERIFY_URL, payload);
          const serverData = response?.data || {};

          registrationData.registrationId = Number(serverData.registration_id || 0) || null;
          registrationData.registrationStatus = String(serverData.registration_status || '');
          registrationData.passCode = String(serverData.pass_code || '');
          registrationData.mailSent = Boolean(serverData.mail_sent);

          renderSuccessStep(serverData);
        } catch (error) {
          message.style.display = 'block';
          message.style.border = '2px solid #f1b4ba';
          message.style.background = '#fff3f4';
          message.style.color = '#8b1e2b';
          message.textContent = error instanceof Error ? error.message : 'Unable to verify code.';
        } finally {
          verifyButton.disabled = false;
          verifyButton.textContent = 'Verify & Submit';
        }
      });
    }

    function renderSuccessStep(serverData) {
      const isPending = String(serverData.registration_status || '').toLowerCase() === 'pending';
      const fullName = String(serverData.full_name || `${registrationData.firstName} ${registrationData.lastName}`.trim() || 'Student Name');
      const displayLevel = String(serverData.school_level || registrationData.schoolLevel || 'Participant').toUpperCase();
      const passCode = String(serverData.pass_code || registrationData.passCode || 'N/A');
      const displayEventName = String(serverData.event_name || selectedEvent.title || 'Event');
      const displayLocation = String(serverData.location || selectedEvent.location || 'TBA');
      modalCard.classList.add('success-mode');
      modalContent.innerHTML = `
                <div class="registration-success">
                    <span class="success-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" role="img" focusable="false">
                            <circle cx="12" cy="12" r="9"></circle>
                            <path d="M8.5 12.3 10.8 14.6 15.8 9.6"></path>
                        </svg>
                    </span>
                          <h3>${isPending ? 'Registration Submitted!' : 'Verification Successful!'}</h3>
                    <p class="success-copy">
                          ${isPending
                            ? 'Your registration is now pending review. A demo digital pass email has been sent for testing purposes.'
                            : 'Your registration is complete. A demo digital pass email has been sent. Here is your preview:'}
                    </p>
                        ${serverData.mail_sent === false ? '<p class="success-copy" style="margin-top:-2px; color:#8b1e2b;">Registration was saved, but email sending failed. Check your mail .env settings and try again.</p>' : ''}

                    <div class="success-grid">
                        <div class="pass-card">
                            <div class="pass-top">
                                <div class="pass-kicker">NU LIPA EVENT PASS</div>
                              <span class="pass-tag">${escapeHtml(displayLevel)}</span>
                            </div>
                            <div class="pass-event">${escapeHtml(displayEventName)}</div>
                            <div class="pass-name">${escapeHtml(fullName)}</div>
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
                                        <span>${escapeHtml(selectedEvent.type || 'School Event')}</span>
                                </div>
                                <div class="pass-meta-row">
                                    <span class="pass-meta-icon" aria-hidden="true">
                                        <svg viewBox="0 0 24 24" role="img" focusable="false">
                                            <path d="M12 20s-5.2-4.6-5.2-8.9A5.2 5.2 0 1 1 17.2 11c0 4.3-5.2 9-5.2 9Z"></path>
                                            <circle cx="12" cy="11" r="1.9"></circle>
                                        </svg>
                                    </span>
                                        <span>${escapeHtml(displayLocation)}</span>
                                </div>
                            </div>
                            <div class="pass-divider"></div>
                            <div class="pass-footer">
                                <label>Attendee ID</label>
                                      <span class="pass-code">${escapeHtml(passCode)}</span>
                            </div>
                            <span class="pass-shield" aria-hidden="true">
                                <svg viewBox="0 0 24 24" role="img" focusable="false">
                                    <path d="M12 3l7 3v5c0 4.8-3 8.6-7 10-4-1.4-7-5.2-7-10V6l7-3z"></path>
                                    <path d="M9.5 12l2 2 3.5-3.5"></path>
                                </svg>
                            </span>
                        </div>

                        <div class="qr-card">
                            <div class="qr-top">SCAN AT REGISTRATION</div>
                            <div class="qr-body">
                                <div class="qr-box" aria-hidden="true">
                                    <svg viewBox="0 0 24 24" role="img" focusable="false">
                                        <rect x="2.5" y="2.5" width="8" height="8" rx="1.6"></rect>
                                        <rect x="4.5" y="4.5" width="4" height="4" rx="1"></rect>

                                        <rect x="14.5" y="2.5" width="4" height="4" rx="1"></rect>
                                        <rect x="20" y="2.5" width="1.5" height="1.5" rx=".4"></rect>

                                        <rect x="2.5" y="14.5" width="4" height="4" rx="1"></rect>
                                        <rect x="8.5" y="12.5" width="3.5" height="3.5" rx=".9"></rect>
                                        <rect x="13.5" y="10.5" width="2.5" height="2.5" rx=".7"></rect>
                                        <rect x="17" y="12" width="2" height="2" rx=".6"></rect>
                                        <rect x="19.5" y="15.5" width="2.5" height="2.5" rx=".7"></rect>
                                        <rect x="14" y="18.5" width="3" height="3" rx=".8"></rect>
                                        <rect x="18.5" y="19" width="1.5" height="1.5" rx=".4"></rect>

                                        <rect x="9" y="19" width="2" height="2" rx=".5"></rect>
                                        <rect x="12.2" y="19" width="1.4" height="1.4" rx=".35"></rect>
                                        <rect x="20" y="9" width="2" height="2" rx=".5"></rect>
                                    </svg>
                                </div>
                                    <p class="qr-note">Demo pass code: ${escapeHtml(passCode)}. This preview is for testing while scanner/API integration is pending.</p>
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
      registrationData = {
        firstName: '',
        lastName: '',
        email: '',
        region: '',
        schoolFrom: '',
        schoolLevel: '',
        verificationId: null,
        registrationStatus: '',
        passCode: '',
        registrationId: null,
        mailSent: false,
      };
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