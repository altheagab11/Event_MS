<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  @php
  $hasErrors = isset($errors) && $errors->any();
  @endphp
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Events Management | NU Lipa EMS</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <style>
    :root {
      --blue-900: #183d7e;
      --blue-800: #224381;
      --gold: #f5c36c;
      --ink: #12356b;
      --bg: #f2f3f8;
      --card: #ffffff;
      --line: #23457e;
      --text: #1a3462;
      --muted: #7281a0;
      --radius: 14px;
      --shadow: 7px 7px 0 0 #1f3f79;
    }

    * {
      box-sizing: border-box;
    }

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

    .header {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      gap: 18px;
      margin-bottom: 16px;
    }

    .page-title {
      margin: 0;
      color: var(--ink);
      font-size: 42px;
      line-height: 1.04;
      font-weight: 800;
    }

    .page-subtitle {
      margin: 7px 0 0;
      color: #6f7f9f;
      font-size: 14px;
      font-weight: 500;
    }

    .create-btn {
      border: 0;
      border-radius: 14px;
      background: var(--gold);
      color: #15386f;
      font-weight: 700;
      font-size: 14px;
      padding: 11px 16px;
      display: inline-flex;
      align-items: center;
      gap: 10px;
      box-shadow: 0 2px 8px rgba(17, 42, 84, .16);
    }

    .create-btn svg {
      width: 20px;
      height: 20px;
      stroke: currentColor;
      fill: none;
      stroke-width: 2;
    }

    .filters {
      background: #f8f9fc;
      border: 2px solid #dee2eb;
      border-radius: 14px;
      padding: 14px;
      display: grid;
      grid-template-columns: 1fr auto auto auto auto;
      gap: 10px;
      align-items: center;
      margin-bottom: 18px;
    }

    .search-wrap {
      position: relative;
    }

    .search-wrap svg {
      position: absolute;
      left: 14px;
      top: 50%;
      transform: translateY(-50%);
      width: 16px;
      height: 16px;
      stroke: #93a0b9;
      fill: none;
      stroke-width: 2;
    }

    .search-wrap input,
    .filter-select {
      width: 100%;
      border: 2px solid #e2e5ec;
      background: #fff;
      border-radius: 10px;
      font-family: inherit;
      font-size: 12px;
      font-weight: 500;
      color: #3f547c;
      height: 36px;
      padding: 0 12px;
    }

    .search-wrap input {
      padding-left: 36px;
    }

    .filter-select {
      min-width: 130px;
    }

    .icon-btn {
      width: 36px;
      height: 36px;
      border: 2px solid #e2e5ec;
      border-radius: 10px;
      background: #fff;
      display: grid;
      place-items: center;
      color: #7081a1;
    }

    .icon-btn svg {
      width: 16px;
      height: 16px;
      stroke: currentColor;
      fill: none;
      stroke-width: 2;
    }

    .grid {
      display: grid;
      grid-template-columns: repeat(3, minmax(0, 1fr));
      gap: 16px;
    }

    .event-card {
      background: #fff;
      border: 2px solid #dfe3eb;
      border-radius: 16px;
      overflow: hidden;
      box-shadow: 0 1px 4px rgba(15, 39, 80, .06);
    }

    .event-image {
      height: 154px;
      position: relative;
      background-size: cover;
      background-position: center;
    }

    .event-image.one {
      background-image: linear-gradient(160deg, rgba(27, 42, 77, .2), rgba(16, 32, 61, .45)), url('https://images.unsplash.com/photo-1529156069898-49953e39b3ac?auto=format&fit=crop&w=1200&q=60');
    }

    .event-image.two {
      background-image: linear-gradient(160deg, rgba(15, 25, 47, .25), rgba(13, 27, 59, .48)), url('https://images.unsplash.com/photo-1475721027785-f74eccf877e2?auto=format&fit=crop&w=1200&q=60');
    }

    .event-image.three {
      background-image: linear-gradient(160deg, rgba(18, 37, 73, .2), rgba(13, 31, 65, .42)), url('https://images.unsplash.com/photo-1517457373958-b7bdd4587205?auto=format&fit=crop&w=1200&q=60');
    }

    .badge {
      position: absolute;
      left: 12px;
      top: 12px;
      font-size: 11px;
      font-weight: 700;
      border-radius: 999px;
      padding: 5px 11px;
      color: #fff;
      background: #2c588f;
    }

    .badge.conference {
      background: #1f4a89;
    }

    .event-body {
      padding: 14px 16px 10px;
    }

    .event-title {
      margin: 0 0 8px;
      color: #17396f;
      font-size: 16px;
      font-weight: 800;
      line-height: 1.3;
    }

    .meta {
      display: flex;
      align-items: center;
      gap: 7px;
      color: #5f7397;
      font-size: 13px;
      margin-bottom: 6px;
    }

    .meta svg {
      width: 14px;
      height: 14px;
      stroke: #7a8ead;
      fill: none;
      stroke-width: 2;
    }

    .event-footer {
      margin-top: 12px;
      padding-top: 12px;
      border-top: 1px solid #eceff5;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .status {
      font-size: 11px;
      font-weight: 700;
      color: #18a64e;
      background: #eaf8ef;
      border-radius: 4px;
      padding: 3px 6px;
    }

    .manage {
      color: #56739d;
      font-size: 12px;
      font-weight: 700;
      text-decoration: none;
    }

    @media (max-width: 1180px) {
      .layout {
        grid-template-columns: 1fr;
      }

      .sidebar {
        flex-direction: row;
        align-items: center;
        border-right: 0;
        border-bottom: 1px solid #e3e5ef;
        gap: 10px;
        padding: 12px;
      }

      .sidebar .top,
      .sidebar .bottom {
        display: contents;
      }

      .menu-title,
      .logout {
        display: none;
      }

      .menu {
        display: flex;
        flex-wrap: wrap;
      }

      .header,
      .filters {
        grid-template-columns: 1fr;
        display: grid;
      }

      .grid {
        grid-template-columns: 1fr;
      }
    }

    /* Modal styles for Create New Event */
    .modal-overlay {
      position: fixed;
      inset: 0;
      background: rgba(9, 18, 34, 0.55);
      display: none;
      align-items: center;
      justify-content: center;
      padding: 28px;
      z-index: 1200;
    }

    .modal-overlay.open {
      display: flex;
    }

    .modal {
      /* Keep header/footer visible; allow the body to scroll when needed */
      display: flex;
      flex-direction: column;
      width: 920px;
      max-width: calc(100% - 56px);
      max-height: calc(100vh - 48px);
      background: #fff;
      border-radius: 12px;
      overflow: hidden;
      /* clip header/footer, body will scroll */
      box-shadow: 0 10px 40px rgba(10, 20, 40, 0.3);
    }

    .modal form {
      display: flex;
      flex-direction: column;
      flex: 1 1 auto;
      min-height: 0;
    }

    .modal-header {
      background: var(--blue-900);
      color: var(--gold);
      padding: 22px 24px;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .modal-header h2 {
      margin: 0;
      font-size: 28px;
      font-weight: 800;
      letter-spacing: .3px;
    }

    .modal-header p {
      margin: 6px 0 0;
      font-size: 13px;
      color: rgba(255, 255, 255, 0.9);
      font-weight: 500;
    }

    .modal-close {
      width: 36px;
      height: 36px;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.06);
      color: #fff;
      display: grid;
      place-items: center;
      border: 2px solid rgba(255, 255, 255, 0.12);
      cursor: pointer;
      font-weight: 700;
    }

    .modal-body {
      padding: 20px 22px;
      display: grid;
      gap: 14px;
      overflow-y: auto;
      /* allow the body to take remaining space inside the modal and scroll */
      flex: 1 1 auto;
      min-height: 0;
    }

    .card-panel {
      background: #fff;
      border: 2px solid #eef2f6;
      border-radius: 12px;
      padding: 18px;
    }

    .card-panel h3 {
      margin: 0 0 12px 0;
      font-size: 18px;
      color: var(--blue-900);
      font-weight: 800;
    }

    .event-type-options {
      display: grid;
      grid-template-columns: repeat(2, minmax(0, 1fr));
      gap: 12px;
      margin-bottom: 14px;
    }

    .event-type-option {
      text-align: left;
      border: 2px solid #dbe4f1;
      background: #fff;
      color: #2b3f66;
      border-radius: 10px;
      padding: 12px;
      cursor: pointer;
      font-family: inherit;
    }

    .event-type-option strong {
      display: block;
      font-size: 14px;
      font-weight: 700;
      margin-bottom: 2px;
    }

    .event-type-option small {
      color: #7c8fae;
      font-weight: 500;
      font-size: 12px;
    }

    .event-type-option.active {
      border-color: #213a6e;
      background: #f6f8ff;
      box-shadow: inset 0 0 0 1px #213a6e;
    }

    .row {
      display: flex;
      gap: 12px;
    }

    .col {
      flex: 1;
    }

    .input,
    textarea,
    .select {
      width: 100%;
      padding: 12px 14px;
      border-radius: 10px;
      border: 1.5px solid #e6edf6;
      font-family: inherit;
      font-size: 14px;
      color: #12345a;
      background: #fbfdff;
    }

    textarea {
      min-height: 100px;
      resize: vertical;
    }

    .modal-footer {
      display: flex;
      justify-content: space-between;
      gap: 12px;
      padding: 14px 22px 18px;
      align-items: center;
      border-top: 1px solid #e7edf5;
      background: #fff;
      flex-shrink: 0;
    }

    .btn-cancel {
      border: 2px solid #edf2f6;
      background: #fff;
      color: #233b6a;
      padding: 12px 20px;
      border-radius: 12px;
      font-weight: 800;
    }

    .btn-publish {
      border: 0;
      border-radius: 14px;
      background: var(--gold);
      color: #15386f;
      font-weight: 800;
      font-size: 16px;
      padding: 12px 20px;
      box-shadow: 0 4px 12px rgba(17, 42, 84, .12);
    }

    .field-note {
      margin-top: 8px;
      font-size: 12px;
      color: #7c8fae;
    }

    .upload-input-wrap {
      width: 100%;
      display: flex;
      align-items: center;
      gap: 10px;
      border-radius: 10px;
      border: 1.5px solid #e6edf6;
      background: #fbfdff;
      padding: 8px 10px;
      min-height: 48px;
    }

    .upload-file-input {
      position: absolute;
      opacity: 0;
      width: 1px;
      height: 1px;
      pointer-events: none;
    }

    .upload-file-trigger {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      border: 0;
      border-radius: 8px;
      background: var(--gold);
      color: #15386f;
      font-weight: 700;
      font-size: 13px;
      padding: 9px 12px;
      white-space: nowrap;
      cursor: pointer;
      flex-shrink: 0;
    }

    .upload-file-name {
      min-width: 0;
      color: #8b98b3;
      font-size: 13px;
      font-weight: 500;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    @media (max-width: 900px) {
      .event-type-options {
        grid-template-columns: 1fr;
      }
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
            <svg viewBox="0 0 24 24">
              <rect x="4" y="4" width="6" height="6" rx="1"></rect>
              <rect x="14" y="4" width="6" height="6" rx="1"></rect>
              <rect x="4" y="14" width="6" height="6" rx="1"></rect>
              <rect x="14" y="14" width="6" height="6" rx="1"></rect>
            </svg>
            Dashboard
          </a>
          <a href="{{ route('admin.events') }}" class="active">
            <svg viewBox="0 0 24 24">
              <rect x="3" y="5" width="18" height="16" rx="2"></rect>
              <line x1="8" y1="3" x2="8" y2="7"></line>
              <line x1="16" y1="3" x2="16" y2="7"></line>
            </svg>
            Events
          </a>
          <a href="{{ route('admin.participants') }}">
            <svg viewBox="0 0 24 24">
              <circle cx="12" cy="8" r="4"></circle>
              <path d="M4 20c1.5-3.5 4.5-5 8-5s6.5 1.5 8 5"></path>
            </svg>
            Participants
          </a>
          <a href="{{ route('admin.evaluations') }}">
            <svg viewBox="0 0 24 24">
              <path d="M4 4h16v12H7l-3 4z"></path>
            </svg>
            Evaluations
          </a>
        </nav>
      </div>

      <div class="bottom">
        <form action="{{ route('logout') }}" method="post">
          @csrf
          <button type="submit" class="logout" style="background: transparent; font-family: inherit; cursor: pointer;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
              <polyline points="16 17 21 12 16 7"></polyline>
              <line x1="21" y1="12" x2="9" y2="12"></line>
            </svg>
            LOGOUT
          </button>
        </form>
      </div>
    </aside>

    <main class="content">
      <header class="header">
        <div>
          <h1 class="page-title">Events Management</h1>
          <p class="page-subtitle">Create, view, and organize school gatherings and conferences.</p>
        </div>
        <button class="create-btn" type="button">
          <svg viewBox="0 0 24 24">
            <line x1="12" y1="5" x2="12" y2="19"></line>
            <line x1="5" y1="12" x2="19" y2="12"></line>
          </svg>
          Create New Event
        </button>
      </header>

      @if (session('status'))
      <div style="margin-bottom:14px; border:2px solid #b7ebc6; background:#ebfff1; color:#1f6b39; border-radius:10px; padding:10px 12px; font-size:13px; font-weight:600;">
        {{ session('status') }}
      </div>
      @endif

      <section class="filters" aria-label="Event filters">
        <label class="search-wrap">
          <svg viewBox="0 0 24 24">
            <circle cx="11" cy="11" r="7"></circle>
            <line x1="16.65" y1="16.65" x2="21" y2="21"></line>
          </svg>
          <input type="text" placeholder="Search events...">
        </label>

        <button class="icon-btn" type="button" title="Filter">
          <svg viewBox="0 0 24 24">
            <polygon points="22 3 2 3 10 12 10 19 14 21 14 12 22 3"></polygon>
          </svg>
        </button>

        <select class="filter-select" aria-label="Categories">
          <option>All Categories</option>
        </select>
        <select class="filter-select" aria-label="Months">
          <option>All Months</option>
        </select>
        <div style="display:flex; gap:8px; align-items:center;">
          <select class="filter-select" aria-label="Years" style="min-width:95px;">
            <option>All Years</option>
          </select>
          <button class="icon-btn" type="button" title="Grid">
            <svg viewBox="0 0 24 24">
              <rect x="4" y="4" width="6" height="6"></rect>
              <rect x="14" y="4" width="6" height="6"></rect>
              <rect x="4" y="14" width="6" height="6"></rect>
              <rect x="14" y="14" width="6" height="6"></rect>
            </svg>
          </button>
          <button class="icon-btn" type="button" title="List">
            <svg viewBox="0 0 24 24">
              <line x1="8" y1="6" x2="20" y2="6"></line>
              <line x1="8" y1="12" x2="20" y2="12"></line>
              <line x1="8" y1="18" x2="20" y2="18"></line>
              <line x1="4" y1="6" x2="4.01" y2="6"></line>
              <line x1="4" y1="12" x2="4.01" y2="12"></line>
              <line x1="4" y1="18" x2="4.01" y2="18"></line>
            </svg>
          </button>
        </div>
      </section>

      <section class="grid" aria-label="Events list">
        @forelse ($events as $event)
        @php
        $imageClass = ['one', 'two', 'three'][$loop->index % 3];
        $isActive = \Illuminate\Support\Carbon::parse($event->event_date)->gte(now()->startOfDay());
        $bannerUrl = $event->banner_image ? \Illuminate\Support\Facades\Storage::disk('public')->url($event->banner_image) : null;
        @endphp
        <article class="event-card">
          <div class="event-image {{ $bannerUrl ? '' : $imageClass }}" @if($bannerUrl) style="background-image: linear-gradient(160deg, rgba(18, 37, 73, .2), rgba(13, 31, 65, .42)), url('{{ $bannerUrl }}');" @endif>
            <span class="badge">School Event</span>
          </div>
          <div class="event-body">
            <h3 class="event-title">{{ $event->event_name }}</h3>
            <div class="meta">
              <svg viewBox="0 0 24 24">
                <rect x="3" y="5" width="18" height="16" rx="2"></rect>
                <line x1="8" y1="3" x2="8" y2="7"></line>
                <line x1="16" y1="3" x2="16" y2="7"></line>
              </svg>
              {{ \Illuminate\Support\Carbon::parse($event->event_date)->format('Y-m-d') }}
            </div>
            <div class="meta">
              <svg viewBox="0 0 24 24">
                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                <circle cx="12" cy="10" r="3"></circle>
              </svg>
              {{ $event->location ?: 'TBA' }}
            </div>
            <div class="event-footer">
              <span class="status">{{ $isActive ? 'Active' : 'Ended' }}</span>
              <span class="manage">Event #{{ $event->event_id }}</span>
            </div>
          </div>
        </article>
        @empty
        <article class="event-card" style="grid-column: 1 / -1;">
          <div class="event-body">
            <h3 class="event-title">No events yet</h3>
            <div class="meta">Create your first event using the button above.</div>
          </div>
        </article>
        @endforelse
      </section>
    </main>
  </div>

  <!-- Create Event Modal -->
  <div id="event-modal-overlay" class="modal-overlay{{ $hasErrors ? ' open' : '' }}" aria-hidden="{{ $hasErrors ? 'false' : 'true' }}">
    <div class="modal" role="dialog" aria-modal="true" aria-labelledby="modal-title">
      <div class="modal-header">
        <div>
          <h2 id="modal-title">Create New Event</h2>
          <p>Fill in the details for the upcoming event.</p>
        </div>
        <button type="button" class="modal-close" aria-label="Close modal">✕</button>
      </div>

      <form method="post" action="{{ route('admin.events.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="modal-body">
          @if ($hasErrors)
          <div style="margin-bottom:14px; border:2px solid #f1b4ba; background:#fff3f4; color:#8b1e2b; border-radius:10px; padding:10px 12px; font-size:13px;">
            @foreach ($errors->all() as $error)
            <div>{{ $error }}</div>
            @endforeach
          </div>
          @endif

          <div class="card-panel">
            <h3>Basic Info</h3>

            <input type="hidden" name="event_type" id="event_type" value="{{ old('event_type', 'School Event') }}">
            <div class="event-type-options" role="radiogroup" aria-label="Event type">
              <button type="button" class="event-type-option{{ old('event_type', 'School Event') === 'School Event' ? ' active' : '' }}" data-option="School Event">
                <strong>School Event</strong>
                <small>Regular campus activities</small>
              </button>
              <button type="button" class="event-type-option{{ old('event_type') === 'Conference' ? ' active' : '' }}" data-option="Conference">
                <strong>Conference</strong>
                <small>Conference sessions and presentations</small>
              </button>
            </div>

            <label style="display:block; font-weight:700; color:#233b6a; margin-bottom:8px;">Event Title</label>
            <input class="input" type="text" name="event_name" value="{{ old('event_name') }}" placeholder="e.g. IT Week 2026" required>
          </div>

          <div class="card-panel">
            <h3>Schedule &amp; Location</h3>
            <div class="row">
              <div class="col">
                <label style="display:block; margin-bottom:8px; font-weight:700; color:#233b6a;">Date</label>
                <input class="input" type="date" name="event_date" value="{{ old('event_date') }}" required>
              </div>
              <div class="col">
                <label style="display:block; margin-bottom:8px; font-weight:700; color:#233b6a;">Location / Venue</label>
                <input class="input" type="text" name="location" value="{{ old('location') }}" placeholder="e.g. Main Auditorium">
              </div>
            </div>
          </div>

          <div class="card-panel">
            <h3>Description</h3>
            <label style="display:block; margin-bottom:8px; font-weight:700; color:#233b6a;">Full Description</label>
            <textarea class="input" name="description" placeholder="Write detailed information...">{{ old('description') }}</textarea>

            <label style="display:block; margin:14px 0 8px; font-weight:700; color:#233b6a;">Event Banner Image</label>
            <div class="upload-input-wrap">
              <input class="upload-file-input" id="banner_image" type="file" name="banner_image" accept="image/png,image/jpeg,image/jpg,image/webp">
              <label for="banner_image" class="upload-file-trigger">Choose Image</label>
              <span class="upload-file-name" id="banner_file_name" title="No file selected">No file selected</span>
            </div>
            <div class="field-note">Accepted formats: JPG, JPEG, PNG, WEBP. Max size: 5 MB.</div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn-cancel">Cancel</button>
          <button type="submit" class="btn-publish">Publish Event</button>
        </div>
      </form>
    </div>
  </div>

  <script>
    (function() {
      const openBtn = document.querySelector('.create-btn');
      const overlay = document.getElementById('event-modal-overlay');
      const closeBtn = overlay ? overlay.querySelector('.modal-close') : null;
      const cancelBtn = overlay ? overlay.querySelector('.btn-cancel') : null;
      const eventTypeInput = overlay ? overlay.querySelector('#event_type') : null;
      const eventTypeButtons = overlay ? overlay.querySelectorAll('.event-type-option') : [];
      const bannerInput = overlay ? overlay.querySelector('#banner_image') : null;
      const bannerName = overlay ? overlay.querySelector('#banner_file_name') : null;

      function openModal() {
        if (!overlay) return;
        overlay.classList.add('open');
        overlay.setAttribute('aria-hidden', 'false');
        // focus first input for convenience
        const first = overlay.querySelector('input, textarea, button');
        if (first) first.focus();
      }

      function closeModal() {
        if (!overlay) return;
        overlay.classList.remove('open');
        overlay.setAttribute('aria-hidden', 'true');
      }

      if (openBtn) openBtn.addEventListener('click', openModal);
      if (closeBtn) closeBtn.addEventListener('click', closeModal);
      if (cancelBtn) cancelBtn.addEventListener('click', closeModal);

      if (eventTypeButtons.length > 0 && eventTypeInput) {
        eventTypeButtons.forEach((btn) => {
          btn.addEventListener('click', () => {
            eventTypeButtons.forEach((node) => node.classList.remove('active'));
            btn.classList.add('active');
            eventTypeInput.value = btn.dataset.option || 'School Event';
          });
        });
      }

      if (bannerInput && bannerName) {
        bannerInput.addEventListener('change', () => {
          const selected = bannerInput.files && bannerInput.files.length > 0 ?
            bannerInput.files[0].name :
            'No file selected';
          bannerName.textContent = selected;
          bannerName.title = selected;
        });
      }

      // close when clicking outside the modal
      if (overlay) {
        overlay.addEventListener('click', function(e) {
          if (e.target === overlay) closeModal();
        });
      }

      // close on Escape
      document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeModal();
      });
    })();
  </script>
</body>

</html>