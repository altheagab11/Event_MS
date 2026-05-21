@php
$months = [
['value' => 0, 'label' => 'Jan'], ['value' => 1, 'label' => 'Feb'], ['value' => 2, 'label' => 'Mar'],
['value' => 3, 'label' => 'Apr'], ['value' => 4, 'label' => 'May'], ['value' => 5, 'label' => 'Jun'],
['value' => 6, 'label' => 'Jul'], ['value' => 7, 'label' => 'Aug'], ['value' => 8, 'label' => 'Sep'],
['value' => 9, 'label' => 'Oct'], ['value' => 10, 'label' => 'Nov'], ['value' => 11, 'label' => 'Dec'],
];

$regions = [
'NCR (National Capital Region)', 'CAR (Cordillera Administrative Region)', 'Region I (Ilocos Region)', 'Region II (Cagayan Valley)',
'Region III (Central Luzon)', 'Region IV-A (CALABARZON)', 'MIMAROPA Region', 'Region V (Bicol Region)', 'Region VI (Western Visayas)',
'Region VII (Central Visayas)', 'Region VIII (Eastern Visayas)', 'Region IX (Zamboanga Peninsula)', 'Region X (Northern Mindanao)',
'Region XI (Davao Region)', 'Region XII (SOCCSKSARGEN)', 'Region XIII (Caraga)', 'BARMM (Bangsamoro Autonomous Region in Muslim Mindanao)'
];
@endphp
@php
  // Ensure newest-created events appear first (upper-left)
  if (isset($events)) {
    if ($events instanceof \Illuminate\Support\Collection) {
      $events = $events->sortByDesc(function ($e) {
        $ts = 0;
        if (! empty($e->created_at)) {
          if ($e->created_at instanceof \DateTimeInterface) {
            $ts = (int) $e->created_at->getTimestamp();
          } else {
            $t = strtotime((string) $e->created_at);
            $ts = $t === false ? 0 : (int) $t;
          }
        } elseif (! empty($e->event_date)) {
          if ($e->event_date instanceof \DateTimeInterface) {
            $ts = (int) $e->event_date->getTimestamp();
          } else {
            $t = strtotime((string) $e->event_date);
            $ts = $t === false ? 0 : (int) $t;
          }
        } elseif (! empty($e->start_date)) {
          if ($e->start_date instanceof \DateTimeInterface) {
            $ts = (int) $e->start_date->getTimestamp();
          } else {
            $t = strtotime((string) $e->start_date);
            $ts = $t === false ? 0 : (int) $t;
          }
        } elseif (! empty($e->id) || ! empty($e['id'])) {
          $ts = (int) ($e->id ?? ($e['id'] ?? 0));
        }
        return $ts;
      })->values();
    } elseif (is_array($events)) {
      usort($events, function ($a, $b) {
        $getTs = function ($item) {
          $keys = ['created_at', 'event_date', 'start_date', 'id'];
          foreach ($keys as $k) {
            if (array_key_exists($k, (array) $item) && ! empty($item[$k])) {
              $v = $item[$k];
              if ($v instanceof \DateTimeInterface) return (int) $v->getTimestamp();
              if (is_numeric($v)) return (int) $v;
              if (is_string($v)) {
                $t = strtotime($v);
                return $t === false ? 0 : (int) $t;
              }
            }
          }
          return 0;
        };
        $ta = $getTs($a);
        $tb = $getTs($b);
        return $tb <=> $ta;
      });
    }
  }
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Event Management System</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/landingpage.css') }}">
</head>

<body class="landing-page">
  <div class="landing-scroll">
    <div class="landing-scroll-content">

      {{-- Header: brand only --}}
      <header class="topbar">
        <div class="container topbar-inner">
          <a href="#" class="brand" aria-label="Event Management System home">
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
      {{-- Hero --}}
      <section class="hero" aria-label="Welcome">
        <div class="container hero-inner reveal">
          <span class="hero-badge">
            <span class="dot" aria-hidden="true"></span>
            Your Academic Events Portal
          </span>
          <h1>Welcome to <span class="accent">Event Management System</span></h1>
          <p>Discover school events, submit research papers, attend conferences, and share your feedback — all in a single, modern portal built for students, faculty, and organizers.</p>

          <div class="hero-ctas">
            <a href="#events" class="cta cta-primary">
              <span>Explore Events</span>
              <span class="cta-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" role="img" focusable="false">
                  <line x1="4" y1="12" x2="20" y2="12"></line>
                  <polyline points="13,5 20,12 13,19"></polyline>
                </svg>
              </span>
            </a>
            <a href="#announcements" class="cta cta-secondary">
              <span class="cta-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" role="img" focusable="false">
                  <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                  <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                </svg>
              </span>
              <span>View Announcements</span>
            </a>
          </div>

          <div class="hero-stats" aria-label="System overview">
            <div>
              <div class="hero-stat-value">{{ count($events) }}+</div>
              <div class="hero-stat-label">Available Events</div>
            </div>
            <div>
              <div class="hero-stat-value">2</div>
              <div class="hero-stat-label">Event Categories</div>
            </div>
            <div>
              <div class="hero-stat-value">100%</div>
              <div class="hero-stat-label">Digital Workflow</div>
            </div>
          </div>
        </div>
      </section>
      {{-- Latest Announcements --}}
      <section class="announce" id="announcements" aria-labelledby="announce-heading">
        <div class="container">
          <div class="announce-head reveal">
            <span class="section-eyebrow">News &amp; Updates</span>
            <h2 id="announce-heading" class="section-heading">Latest Announcements</h2>
            <p class="section-subheading">Important updates and reminders from the organizing team.</p>
          </div>

          <div class="announce-grid">
            @forelse ($announcements ?? [] as $item)
              @php $isImportant = ($item['type'] ?? '') === 'important'; @endphp
              <article class="announce-card {{ $isImportant ? 'important' : '' }} reveal">
                <div class="announce-card-head">
                  <span class="announce-icon-tile" aria-hidden="true">{{ $item['emoji'] ?? '' }}</span>
                  <span class="announce-tag">{{ $isImportant ? 'Important' : 'Update' }}</span>
                </div>
                <div class="announce-card-body">
                  <h3>{{ $item['title'] }}</h3>
                  <p>{{ $item['description'] }}</p>
                </div>
                @if (($item['cta'] ?? 'register') === 'events' || empty($item['can_register']))
                  <a href="#events" class="announce-btn ghost">{{ $item['buttonText'] }}</a>
                @else
                  <button type="button" class="announce-btn gold open-register" data-event-id="{{ $item['eventId'] }}">{{ $item['buttonText'] }}</button>
                @endif
              </article>
            @empty
              <article class="announce-card reveal" style="grid-column: 1 / -1;">
                <div class="announce-card-body">
                  <h3>No announcements yet</h3>
                  <p>Published events will appear here with registration updates and reminders.</p>
                </div>
                <a href="#events" class="announce-btn ghost">Browse Events</a>
              </article>
            @endforelse
          </div>
        </div>
      </section>

      {{-- Discover Events --}}
      <section id="events" class="events" aria-labelledby="events-heading">
        <div class="container">
          <div class="events-head reveal">
            <span class="section-eyebrow">Event Catalog</span>
            <h2 id="events-heading">Discover Events</h2>
            <p>Browse upcoming school gatherings and major academic conferences. Filter by category or month to find what interests you.</p>
          </div>

          <div class="filters">
            <div class="filter-group" role="tablist" aria-label="Event category">
              <button class="fbtn active" data-category="All" type="button">
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
              <button class="fbtn" data-category="School Event" type="button">
                <span class="filter-icon" aria-hidden="true">
                  <svg viewBox="0 0 24 24" role="img" focusable="false">
                    <path d="M3 9l9-4 9 4-9 4-9-4z"></path>
                    <path d="M6.5 10.6V14c0 2.1 2.6 3.8 5.5 3.8s5.5-1.7 5.5-3.8v-3.4"></path>
                  </svg>
                </span>
                <span>School</span>
              </button>
              <button class="fbtn" data-category="Conference Event" type="button">
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

            <button class="fmonth" id="toggleDateFilter" type="button" aria-haspopup="true">
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

            <div class="date-pop" id="dateFilterPop" role="dialog" aria-label="Filter events by date">
              <label for="yearInput">Year</label>
              <input id="yearInput" type="number" placeholder="e.g. 2026">
              <label>Month</label>
              <div class="month-grid" id="monthBtns">
                <button class="mbtn month-all active" data-month="All" type="button">All Months</button>
                @foreach($months as $m)
                  <button class="mbtn" data-month="{{ $m['value'] }}" type="button">{{ $m['label'] }}</button>
                @endforeach
              </div>
              <div class="dp-actions">
                <button class="clear" id="clearDateFilter" type="button">Clear</button>
                <button class="apply" id="applyDateFilter" type="button">Apply</button>
              </div>
            </div>
          </div>

          <div class="events-grid" id="eventsGrid">
            @forelse ($events as $event)
              <article class="event-card reveal" data-id="{{ $event['id'] }}" data-type="{{ $event['type'] }}" data-month="{{ $event['month'] }}" data-year="{{ $event['year'] }}">
                <div class="event-media">
                  <img src="{{ $event['image'] }}" alt="{{ $event['title'] }}" loading="lazy">
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
                    <span class="meta-row">
                      <span class="meta-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" role="img" focusable="false">
                          <rect x="3.5" y="5.5" width="17" height="13" rx="2"></rect>
                          <line x1="3.5" y1="10" x2="20.5" y2="10"></line>
                          <line x1="8" y1="14" x2="10" y2="14"></line>
                          <line x1="12" y1="14" x2="14" y2="14"></line>
                          <line x1="16" y1="14" x2="18" y2="14"></line>
                        </svg>
                      </span>
                      <span>{{ $event['attendance_format'] }}</span>
                    </span>
                  </p>
                  <p class="desc">{{ $event['description'] }}</p>
                  @if ($event['type'] === 'Conference Event')
                    <div class="sample-file-wrap">
                      <p class="sample-file-label">Sample File</p>
                      @if (! empty($event['paper_format_url']))
                        <a
                          href="{{ $event['paper_format_url'] }}"
                          class="sample-file-btn"
                          target="_blank"
                          rel="noopener noreferrer"
                          download
                        >
                          <span class="sample-file-btn-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" role="img" focusable="false">
                              <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                              <polyline points="14,2 14,8 20,8"></polyline>
                              <line x1="12" y1="18" x2="12" y2="12"></line>
                              <polyline points="9,15 12,18 15,15"></polyline>
                            </svg>
                          </span>
                          <span>View / Download Sample File</span>
                        </a>
                      @else
                        <p class="sample-file-unavailable">Sample file will be posted soon.</p>
                      @endif
                    </div>
                  @endif
                  <div class="event-actions">
                    @if ($event['can_register'])
                      <button class="event-btn open-register" data-event-id="{{ $event['id'] }}" type="button">
                        <span>{{ $event['card_label'] }}</span>
                        <span class="cta-icon" aria-hidden="true">
                          <svg viewBox="0 0 24 24" role="img" focusable="false">
                            <line x1="4" y1="12" x2="20" y2="12"></line>
                            <polyline points="13,5 20,12 13,19"></polyline>
                          </svg>
                        </span>
                      </button>
                    @else
                      <button
                        class="event-btn outline{{ $event['card_state'] === 'completed' ? ' event-btn-completed' : '' }}"
                        type="button"
                        disabled
                        aria-disabled="true"
                      >
                        <span>{{ $event['card_label'] }}</span>
                      </button>
                    @endif
                  </div>
                </div>
              </article>
            @empty
              <article class="event-card" style="grid-column: 1 / -1;">
                <div class="event-body">
                  <h3 class="event-title">No events available</h3>
                  <p class="desc">Admin has not published any events yet. Please check back soon.</p>
                </div>
              </article>
            @endforelse
          </div>
        </div>
      </section>

      {{-- Why Use This System --}}
      <section class="features" id="features" aria-labelledby="features-heading">
        <div class="container">
          <div class="features-head reveal">
            <span class="section-eyebrow">Why Use This System</span>
            <h2 id="features-heading" class="section-heading">Everything you need for academic events</h2>
            <p class="section-subheading">From quick event sign-ups to research conference submissions and post-event evaluations — all powered by a single, streamlined workflow.</p>
          </div>

          <div class="features-grid">
            <article class="feature-card reveal">
              <span class="feature-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" role="img" focusable="false">
                  <path d="M9 4h6a2 2 0 0 1 2 2v0H7v0a2 2 0 0 1 2-2z"></path>
                  <rect x="5" y="4" width="14" height="17" rx="2"></rect>
                  <path d="M9 12l2 2 4-4"></path>
                </svg>
              </span>
              <h3 class="feature-title">Event Registration</h3>
              <p class="feature-desc">Sign up for school events or conferences in seconds with a guided, email-verified form.</p>
            </article>

            <article class="feature-card reveal delay-1">
              <span class="feature-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" role="img" focusable="false">
                  <path d="M14 3H7a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8z"></path>
                  <polyline points="14 3 14 8 19 8"></polyline>
                  <line x1="8" y1="13" x2="16" y2="13"></line>
                  <line x1="8" y1="17" x2="14" y2="17"></line>
                </svg>
              </span>
              <h3 class="feature-title">Conference Submission</h3>
              <p class="feature-desc">Submit your research paper for review and track its approval status through the portal.</p>
            </article>

            <article class="feature-card reveal delay-2">
              <span class="feature-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" role="img" focusable="false">
                  <polygon points="12,3 14.7,9 21,9.7 16.5,14 17.8,20.4 12,17.3 6.2,20.4 7.5,14 3,9.7 9.3,9"></polygon>
                </svg>
              </span>
              <h3 class="feature-title">Evaluation Forms</h3>
              <p class="feature-desc">Share your feedback to help organizers improve future events with star ratings and comments.</p>
            </article>

            <article class="feature-card reveal delay-3">
              <span class="feature-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" role="img" focusable="false">
                  <rect x="3" y="3" width="7" height="7" rx="1"></rect>
                  <rect x="14" y="3" width="7" height="7" rx="1"></rect>
                  <rect x="3" y="14" width="7" height="7" rx="1"></rect>
                  <path d="M14 14h3v3h-3z"></path>
                  <path d="M20 14v3"></path>
                  <path d="M14 20h3"></path>
                  <path d="M20 20h1"></path>
                </svg>
              </span>
              <h3 class="feature-title">Digital QR Pass / ID</h3>
              <p class="feature-desc">Get a digital event pass for fast, paperless check-in straight at the venue entrance.</p>
            </article>

            <article class="feature-card reveal delay-4">
              <span class="feature-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" role="img" focusable="false">
                  <rect x="3" y="5" width="18" height="14" rx="2"></rect>
                  <path d="M3 7l9 6 9-6"></path>
                </svg>
              </span>
              <h3 class="feature-title">Email Notifications</h3>
              <p class="feature-desc">Receive confirmations, reminders, and important updates directly in your inbox.</p>
            </article>

            <article class="feature-card reveal delay-5">
              <span class="feature-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" role="img" focusable="false">
                  <path d="M3 12h4l3-8 4 16 3-8h4"></path>
                </svg>
              </span>
              <h3 class="feature-title">Event Status Tracking</h3>
              <p class="feature-desc">See upcoming, ongoing, and completed events at a glance with clear visual cues.</p>
            </article>
          </div>
        </div>
      </section>

      {{-- How It Works --}}
      <section class="how" id="how-it-works" aria-labelledby="how-heading">
        <div class="container">
          <div class="how-head reveal">
            <span class="section-eyebrow">How It Works</span>
            <h2 id="how-heading" class="section-heading">Three simple steps</h2>
            <p class="section-subheading">Find what interests you, fill out a quick form, and you're ready to participate.</p>
          </div>

          <div class="how-grid">
            <article class="step-card reveal">
              <div class="step-num">1</div>
              <h3 class="step-title">Browse Available Events</h3>
              <p class="step-desc">Discover upcoming school events and academic conferences by category or month.</p>
            </article>
            <article class="step-card reveal delay-1">
              <div class="step-num">2</div>
              <h3 class="step-title">Register or Submit Requirements</h3>
              <p class="step-desc">Complete the registration form, and upload your research paper if applicable.</p>
            </article>
            <article class="step-card reveal delay-2">
              <div class="step-num">3</div>
              <h3 class="step-title">Receive Updates &amp; Participate</h3>
              <p class="step-desc">Get email confirmations, your digital pass, and friendly reminders along the way.</p>
            </article>
          </div>
        </div>
      </section>


      {{-- Footer --}}
      <footer class="footer" aria-label="Site footer">
        <div class="container">
          <div class="footer-grid">
            <div class="footer-brand">
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
              <p class="footer-desc">A unified platform for school events, academic conferences, paper submissions, and post-event evaluations — designed for students, faculty, and organizers.</p>
            </div>

            <nav class="footer-nav" aria-label="Footer quick links">
              <h4 class="footer-links-title">Quick Links</h4>
              <ul class="footer-links">
                <li><a href="#features">Why Use This System</a></li>
                <li><a href="#how-it-works">How It Works</a></li>
                <li><a href="#announcements">Latest Announcements</a></li>
                <li><a href="#events">Discover Events</a></li>
              </ul>
            </nav>
          </div>

          <div class="footer-bottom">
            <p class="fcopy">© {{ date('Y') }} Event Management System. All rights reserved.</p>
            <p class="fcopy">Built for typical school events and academic conferences.</p>
          </div>
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
    const SCHOOL_UNIVERSITIES = [
      'National University Lipa',
      'Batangas State University',
      'De La Salle Lipa',
      'University of Santo Tomas',
      'Polytechnic University of the Philippines',
      'Other',
    ];
    const USER_TYPES = [
      'Student',
      'Faculty',
      'Admin',
    ];
    const CONFERENCE_ROLES = [
      'Presentor',
      'Participant',
    ];
    const SCHOOL_ROLES = [
      'Exhibitor',
      'Participant',
    ];

    function isConferenceEvent(eventData) {
      return String(eventData?.type || '').trim() === 'Conference Event';
    }

    function rolesForEvent(eventData) {
      return isConferenceEvent(eventData) ? CONFERENCE_ROLES : SCHOOL_ROLES;
    }

    function isPresenterRole(role) {
      return String(role || '').trim().toLowerCase() === 'presentor';
    }

    function requiresPaperUpload(eventData, role) {
      return isConferenceEvent(eventData) && isPresenterRole(role);
    }
    const REGISTER_SEND_URL = @json(route('registration.send-verification'));
    const REGISTER_RESEND_URL = @json(route('registration.resend-code'));
    const REGISTER_VERIFY_URL = @json(route('registration.verify-code'));
    const EVALUATION_SUBMIT_BASE_URL = @json(url('/events'));
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
      attendanceMode: '',
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

    function renderSelectOptions(options, placeholder) {
      const safeOptions = Array.isArray(options) ? options : [];
      return `
        <option value="" selected disabled>${escapeHtml(placeholder)}</option>
        ${safeOptions.map((option) => `<option value="${escapeHtml(option)}">${escapeHtml(option)}</option>`).join('')}
      `;
    }

    function getAttendanceFormat(eventData) {
      const value = String(eventData?.attendance_format ?? '').trim();
      return value !== '' ? value : 'Not Specified';
    }

    function isHybridAttendanceFormat(eventData) {
      return getAttendanceFormat(eventData) === 'Hybrid';
    }

    function resolvePassPreviewContext(eventData, selectedAttendanceMode = '') {
      const format = getAttendanceFormat(eventData);
      let mode = 'Face-to-Face';

      if (format === 'Online') {
        mode = 'Online';
      } else if (format === 'Hybrid') {
        const selected = String(selectedAttendanceMode || '').trim();
        mode = selected !== '' ? selected : 'Face-to-Face';
      }

      const usesVenueScan = mode.toLowerCase() === 'face-to-face';

      return {
        attendanceMode: mode,
        checkinMethod: usesVenueScan ? 'Venue QR Scan' : 'Online Attendance Link',
        qrTitle: usesVenueScan ? 'SCAN AT VENUE' : 'ONLINE CHECK-IN',
        qrNotePending: usesVenueScan
          ? 'Your digital pass with venue QR code will be emailed after admin approval.'
          : 'Your digital pass with online check-in access will be emailed after admin approval.',
        qrNoteApproved: usesVenueScan
          ? 'Present your pass QR at the venue for staff to scan and record attendance.'
          : 'Use your pass QR or online check-in link to confirm attendance during the session.',
      };
    }

    function renderAttendanceModeField() {
      return `
        <div id="attendanceModeWrap" class="attendance-mode-wrap" style="display:none">
          <label class="attendance-mode-label">Attendance Mode *</label>
          <div class="attendance-mode-options">
            <label class="attendance-mode-option">
              <input type="radio" name="attendance_mode" value="Face-to-Face">
              <span>Face-to-Face</span>
            </label>
            <label class="attendance-mode-option">
              <input type="radio" name="attendance_mode" value="Online">
              <span>Online</span>
            </label>
          </div>
        </div>
      `;
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
        const validationMessage = payload.errors ?
          Object.values(payload.errors).flat().join(' ') :
          '';
        throw new Error(validationMessage || payload.message || 'Request failed.');
      }

      return payload;
    }

    function makeOtpInputs() {
      return `<div class="otp-row">${Array.from({ length: 6 }, (_, i) => `<input maxlength="1" data-otp="${i}" required>`).join('')}</div>`;
    }

    function renderFormStep() {
      const eventRoles = rolesForEvent(selectedEvent);
      const attendanceFormat = escapeHtml(getAttendanceFormat(selectedEvent));
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
                        <div class="chip-item">
                          <span class="chip-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" role="img" focusable="false">
                              <rect x="3.5" y="5.5" width="17" height="13" rx="2"></rect>
                              <line x1="3.5" y1="10" x2="20.5" y2="10"></line>
                              <line x1="8" y1="14" x2="10" y2="14"></line>
                              <line x1="12" y1="14" x2="14" y2="14"></line>
                              <line x1="16" y1="14" x2="18" y2="14"></line>
                            </svg>
                          </span>
                          <span>Attendance: ${attendanceFormat}</span>
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
                            <div class="field"><label>School / University *</label><input name="schoolUniversity" placeholder="Enter school / university" required></div>
                            <div class="field"><label>User Type *</label><select name="userType" required>${renderSelectOptions(USER_TYPES, 'Select user type')}</select></div>
                            <div class="field"><label>Role *</label><select name="role" id="registrationRole" required>${renderSelectOptions(eventRoles, 'Select role')}</select></div>
                        </div>
                        ${renderAttendanceModeField()}
                        <div class="upload-wrap" id="paperFormatDownloadWrap" style="display:none">
                            <div class="upload-title">Required Paper Format</div>
                            <div class="upload-note">Download the official format below, then upload your completed research paper as PDF.</div>
                            <a class="format-download-btn" id="paperFormatDownloadLink" href="#" target="_blank" rel="noopener noreferrer">Download paper format</a>
                        </div>
                        <div class="upload-wrap" id="paperUploadWrap" style="display:none">
                            <div class="upload-title">Upload 5-page Research Paper (PDF only)</div>
                            <div class="upload-note">Your paper will be subject to admin review before your registration is fully confirmed.</div>
                          <label class="upload-drop" id="paperUploadDrop">
                            <input class="upload-file-input" type="file" name="paperFile" accept=".pdf">
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
                                <span class="upload-file-ready" id="paperUploadReady" hidden>PDF attached and ready for submission.</span>
                                <span class="upload-file-meta" id="paperUploadMeta" hidden></span>
                            </label>
                        </div>
                        <button class="submit" type="submit" id="continueRegistrationBtn">Continue Registration</button>
                    </form>
                </div>
            `;
      const registrationForm = document.getElementById('registrationForm');
      const submitButton = document.getElementById('continueRegistrationBtn');
      const roleSelect = document.getElementById('registrationRole');
      const paperFormatDownloadWrap = document.getElementById('paperFormatDownloadWrap');
      const paperFormatDownloadLink = document.getElementById('paperFormatDownloadLink');
      const paperUploadWrap = document.getElementById('paperUploadWrap');
      const paperInput = registrationForm.querySelector('.upload-file-input');
      const paperUploadDrop = document.getElementById('paperUploadDrop');
      const paperUploadReady = document.getElementById('paperUploadReady');
      const paperUploadMeta = document.getElementById('paperUploadMeta');
      const uploadDropMain = registrationForm.querySelector('.upload-drop-main');
      const attendanceModeWrap = document.getElementById('attendanceModeWrap');

      function syncAttendanceModeVisibility() {
        const showAttendanceMode = isHybridAttendanceFormat(selectedEvent);
        if (!attendanceModeWrap) {
          return;
        }

        attendanceModeWrap.style.display = showAttendanceMode ? 'block' : 'none';
        attendanceModeWrap.querySelectorAll('input[name="attendance_mode"]').forEach((input) => {
          input.required = showAttendanceMode;
          if (!showAttendanceMode) {
            input.checked = false;
          }
        });
      }

      function syncPaperUploadVisibility() {
        const showPaper = requiresPaperUpload(selectedEvent, roleSelect?.value || '');
        const formatUrl = String(selectedEvent?.paper_format_url || '').trim();
        const showFormatDownload = showPaper && formatUrl !== '';

        if (paperFormatDownloadWrap) {
          paperFormatDownloadWrap.style.display = showFormatDownload ? 'block' : 'none';
        }

        if (paperFormatDownloadLink && showFormatDownload) {
          paperFormatDownloadLink.href = formatUrl;
        }

        if (paperUploadWrap) {
          paperUploadWrap.style.display = showPaper ? 'block' : 'none';
        }

        if (paperInput) {
          paperInput.required = showPaper;
          if (!showPaper) {
            paperInput.value = '';
            setUploadUi(null);
          }
        }
      }

      function formatBytes(bytes) {
        if (!Number.isFinite(bytes) || bytes <= 0) {
          return '0 B';
        }
        if (bytes < 1024) {
          return `${bytes} B`;
        }
        const kb = bytes / 1024;
        if (kb < 1024) {
          return `${kb.toFixed(2)} KB`;
        }
        return `${(kb / 1024).toFixed(2)} MB`;
      }

      function resolveFileTypeLabel(file) {
        if (file.type && file.type.trim() !== '') {
          return file.type;
        }

        const parts = String(file.name || '').split('.');
        const extension = parts.length > 1 ? String(parts.pop() || '').toUpperCase() : '';
        return extension !== '' ? `${extension} file` : 'Unknown type';
      }

      function setUploadUi(file) {
        if (!requiresPaperUpload(selectedEvent, roleSelect?.value || '') || !paperUploadDrop || !paperUploadReady || !paperUploadMeta || !uploadDropMain) {
          return;
        }

        const isReady = file instanceof File && file.size > 0;

        if (!isReady) {
          paperUploadDrop.classList.remove('has-file');
          uploadDropMain.textContent = 'Click or drag PDF to upload';
          paperUploadReady.hidden = true;
          paperUploadMeta.hidden = true;
          paperUploadMeta.textContent = '';
          submitButton.textContent = 'Continue Registration';
          return;
        }

        paperUploadDrop.classList.add('has-file');
        uploadDropMain.textContent = 'PDF attached';
        paperUploadReady.hidden = false;
        paperUploadMeta.hidden = false;

        const fileName = String(file.name || 'unnamed.pdf');
        const typeLabel = resolveFileTypeLabel(file);
        const sizeLabel = formatBytes(Number(file.size || 0));
        paperUploadMeta.textContent = `${fileName} | ${typeLabel} | ${sizeLabel}`;
        submitButton.textContent = 'Continue Registration (PDF Ready)';
      }

      if (roleSelect) {
        roleSelect.addEventListener('change', syncPaperUploadVisibility);
      }

      if (paperInput) {
        paperInput.addEventListener('change', () => {
          const selectedFile = paperInput.files && paperInput.files.length > 0 ? paperInput.files[0] : null;
          setUploadUi(selectedFile);
        });
      }

      syncPaperUploadVisibility();
      syncAttendanceModeVisibility();

      registrationForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        const formData = new FormData(e.target);
        const message = document.getElementById('registerFormMessage');

        const payload = new FormData();
        payload.append('event_id', String(selectedEvent.id));
        payload.append('first_name', String(formData.get('firstName') || '').trim());
        payload.append('last_name', String(formData.get('lastName') || '').trim());
        payload.append('email', String(formData.get('email') || '').trim());
        payload.append('region', String(formData.get('schoolUniversity') || '').trim());
        payload.append('school_from', String(formData.get('userType') || '').trim());
        payload.append('school_level', String(formData.get('role') || '').trim());

        const selectedAttendanceMode = isHybridAttendanceFormat(selectedEvent)
          ? String(formData.get('attendance_mode') || '').trim()
          : (getAttendanceFormat(selectedEvent) === 'Online' ? 'Online' : 'Face-to-Face');

        if (isHybridAttendanceFormat(selectedEvent)) {
          payload.append('attendance_mode', selectedAttendanceMode);
        }

        const selectedRole = String(formData.get('role') || '').trim();
        const paperFile = formData.get('paperFile');
        if (requiresPaperUpload(selectedEvent, selectedRole) && paperFile instanceof File && paperFile.size > 0) {
          payload.append('paper_file', paperFile);
        }

        registrationData = {
          firstName: String(formData.get('firstName') || '').trim(),
          lastName: String(formData.get('lastName') || '').trim(),
          email: String(formData.get('email') || '').trim(),
          region: String(formData.get('schoolUniversity') || '').trim(),
          schoolFrom: String(formData.get('userType') || '').trim(),
          schoolLevel: String(formData.get('role') || '').trim(),
          verificationId: null,
          registrationStatus: '',
          passCode: '',
          attendanceMode: selectedAttendanceMode,
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
          if (requiresPaperUpload(selectedEvent, roleSelect?.value || '') && paperInput) {
            const selectedFile = paperInput.files && paperInput.files.length > 0 ? paperInput.files[0] : null;
            setUploadUi(selectedFile);
          } else {
            submitButton.textContent = 'Continue Registration';
          }
        }
      });
    }

    function renderOtpStep(maskedEmail) {
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
                    <div style="margin-top:14px; color:#6b7280;" id="countdown">Resend available in 0:59</div>
                    <div style="margin-top:10px; color:#6b7280; font-size:13px;">
                      Didn't receive the code?
                      <button type="button" id="resendCodeBtn" style="border:0; background:transparent; color:#1d4d95; font-weight:700; cursor:pointer; text-decoration:underline;">Resend Code</button>
                    </div>
                </div>
            `;

      const otpInputs = Array.from(document.querySelectorAll('[data-otp]'));
      const message = document.getElementById('otpMessage');
      const countdownElement = document.getElementById('countdown');
      const resendButton = document.getElementById('resendCodeBtn');

      function showOtpMessage(kind, text) {
        message.style.display = 'block';
        message.style.border = kind === 'success' ? '2px solid #b7ebc6' : '2px solid #f1b4ba';
        message.style.background = kind === 'success' ? '#ebfff1' : '#fff3f4';
        message.style.color = kind === 'success' ? '#1f6b39' : '#8b1e2b';
        message.textContent = text;
      }

      function updateCountdownUi() {
        if (countdown > 0) {
          countdownElement.textContent = `Resend available in 0:${String(countdown).padStart(2, '0')}`;
          resendButton.disabled = true;
          resendButton.style.opacity = '.65';
          resendButton.style.cursor = 'not-allowed';
        } else {
          countdownElement.textContent = 'You can request a new code now.';
          resendButton.disabled = false;
          resendButton.style.opacity = '1';
          resendButton.style.cursor = 'pointer';
        }
      }

      function resetOtpTimer(seconds = 59) {
        countdown = seconds;
        updateCountdownUi();
        if (otpTimer) clearInterval(otpTimer);
        otpTimer = setInterval(() => {
          countdown -= 1;
          if (countdown <= 0) {
            countdown = 0;
            clearInterval(otpTimer);
          }
          updateCountdownUi();
        }, 1000);
      }

      otpInputs.forEach((input, idx) => {
        input.addEventListener('input', (e) => {
          e.target.value = e.target.value.slice(-1).toUpperCase();
          if (e.target.value && idx < otpInputs.length - 1) otpInputs[idx + 1].focus();
        });
        input.addEventListener('keydown', (e) => {
          if (e.key === 'Backspace' && !e.target.value && idx > 0) otpInputs[idx - 1].focus();
        });
      });

      resetOtpTimer(59);

      resendButton.addEventListener('click', async function() {
        const payload = new FormData();
        payload.append('verification_id', String(registrationData.verificationId || ''));

        resendButton.disabled = true;
        resendButton.textContent = 'Resending...';

        try {
          const response = await postForm(REGISTER_RESEND_URL, payload);
          registrationData.verificationId = Number(response?.data?.verification_id || registrationData.verificationId || 0) || registrationData.verificationId;

          otpInputs.forEach(input => {
            input.value = '';
          });
          if (otpInputs.length > 0) {
            otpInputs[0].focus();
          }

          showOtpMessage('success', response?.message || 'A new verification code was sent to your email.');
          resetOtpTimer(59);
        } catch (error) {
          showOtpMessage('error', error instanceof Error ? error.message : 'Unable to resend verification code.');
          updateCountdownUi();
        } finally {
          resendButton.textContent = 'Resend Code';
          if (countdown === 0) {
            resendButton.disabled = false;
          }
        }
      });

      document.getElementById('otpForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const verifyButton = document.getElementById('verifyOtpBtn');
        const code = otpInputs.map(input => input.value.trim()).join('').toUpperCase();

        if (code.length !== 6) {
          showOtpMessage('error', 'Please enter the 6-character verification code.');
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
          registrationData.attendanceMode = String(serverData.attendance_mode || registrationData.attendanceMode || '');
          registrationData.mailSent = Boolean(serverData.mail_sent);

          renderSuccessStep(serverData);
        } catch (error) {
          showOtpMessage('error', error instanceof Error ? error.message : 'Unable to verify code.');
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
      const passCode = isPending
        ? 'PENDING APPROVAL'
        : String(serverData.pass_code || registrationData.passCode || 'N/A');
      const displayEventName = String(serverData.event_name || selectedEvent.title || 'Event');
      const displayLocation = String(serverData.location || selectedEvent.location || 'TBA');
      const passPreview = resolvePassPreviewContext(
        selectedEvent,
        String(serverData.attendance_mode || registrationData.attendanceMode || '')
      );
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
                            ? 'Your registration is now pending admin approval. Your Digital ID will be sent by email once approved.'
                            : 'Your registration is complete. Your digital pass will be sent by email once approved.'}
                    </p>
                        ${!isPending && serverData.mail_sent === false ? '<p class="success-copy" style="margin-top:-2px; color:#8b1e2b;">Registration was saved, but email sending failed. Check your mail .env settings and try again.</p>' : ''}

                    <div class="success-grid" style="${isPending ? 'display:none;' : ''}">
                        <div class="pass-card">
                            <div class="pass-top">
                                <div class="pass-kicker">EVENT PASS</div>
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
                                <div class="pass-meta-row">
                                    <span class="pass-meta-icon" aria-hidden="true">
                                        <svg viewBox="0 0 24 24" role="img" focusable="false">
                                            <path d="M4 6h16v4H4z"></path>
                                            <path d="M4 14h10v4H4z"></path>
                                            <path d="M18 14h2v4h-2z"></path>
                                        </svg>
                                    </span>
                                    <span>Attendance Mode: ${escapeHtml(passPreview.attendanceMode)}</span>
                                </div>
                                <div class="pass-meta-row">
                                    <span class="pass-meta-icon" aria-hidden="true">
                                        <svg viewBox="0 0 24 24" role="img" focusable="false">
                                            <path d="M12 3v7"></path>
                                            <path d="M8 7h8"></path>
                                            <path d="M5 12h14v8H5z"></path>
                                        </svg>
                                    </span>
                                    <span>Check-in Method: ${escapeHtml(passPreview.checkinMethod)}</span>
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
                            <div class="qr-top">${escapeHtml(passPreview.qrTitle)}</div>
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
                                    <p class="qr-note">${isPending ? escapeHtml(passPreview.qrNotePending) : escapeHtml(passPreview.qrNoteApproved)}</p>
                            </div>
                        </div>
                    </div>

                            <div class="success-actions" style="${isPending ? 'display:none;' : ''}">
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
      if (!selectedEvent || !selectedEvent.can_register) return;
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
      let evalEmail = '';
      let evalComment = '';

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
                          <label class="eval-label">EMAIL ADDRESS *</label>
                          <input class="eval-text" id="evalEmailInput" type="email" placeholder="Enter your registered email" value="${escapeHtml(evalEmail)}" required>
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
                              <textarea class="eval-text" id="evalCommentInput" rows="4" placeholder="What did you like? What can we improve?">${escapeHtml(evalComment)}</textarea>
                              <div id="evalMessage" style="display:none; margin:10px 0 12px; padding:10px 12px; border-radius:10px; font-size:13px; font-weight:600;"></div>
                            <button type="button" class="eval-submit" id="evalSubmitBtn" ${rating === 0 ? 'disabled' : ''}>Submit Evaluation</button>
                        </div>
                    </div>
                `;

        const stars = Array.from(document.querySelectorAll('.rating-star'));
        const submitBtn = document.getElementById('evalSubmitBtn');
        const emailInput = document.getElementById('evalEmailInput');
        const commentInput = document.getElementById('evalCommentInput');
        const evalMessage = document.getElementById('evalMessage');

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
          const email = String(emailInput.value || '').trim();
          const comment = String(commentInput.value || '').trim();

          evalEmail = email;
          evalComment = comment;

          if (!email) {
            evalMessage.style.display = 'block';
            evalMessage.style.border = '2px solid #f1b4ba';
            evalMessage.style.background = '#fff3f4';
            evalMessage.style.color = '#8b1e2b';
            evalMessage.textContent = 'Email is required to verify your participation.';
            return;
          }

          const payload = new FormData();
          payload.append('email', email);
          payload.append('rating', String(rating));
          payload.append('comment', comment);

          evalMessage.style.display = 'none';
          submitBtn.disabled = true;
          submitBtn.textContent = 'Submitting...';

          postForm(`${EVALUATION_SUBMIT_BASE_URL}/${selectedEvent.id}/evaluate`, payload)
            .then(() => {
              evalSubmitted = true;
              renderEvaluateState();
            })
            .catch((error) => {
              evalMessage.style.display = 'block';
              evalMessage.style.border = '2px solid #f1b4ba';
              evalMessage.style.background = '#fff3f4';
              evalMessage.style.color = '#8b1e2b';
              evalMessage.textContent = error instanceof Error ? error.message : 'Unable to submit evaluation.';
            })
            .finally(() => {
              submitBtn.disabled = rating === 0;
              submitBtn.textContent = 'Submit Evaluation';
            });
        });
      }

      renderEvaluateState();
    }

    document.querySelectorAll('.open-register').forEach(btn => {
      btn.addEventListener('click', () => openRegister(btn.dataset.eventId));
    });

    document.getElementById('closeModal').addEventListener('click', closeModal);
    eventModal.addEventListener('click', (e) => {
      if (e.target === eventModal) {
        e.preventDefault();
      }
    });

  </script>
</body>

</html>
