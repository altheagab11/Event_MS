<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  @php
  $hasCreateErrors = isset($errors) && $errors->any();
  $editErrorBag = $errors->getBag('editEvent');
  $hasEditErrors = $editErrorBag->any();
  $editingEventId = (int) old('editing_event_id', 0);
  $editingEvent = $editingEventId > 0 ? $events->firstWhere('event_id', $editingEventId) : null;
  $editingEventDate = $editingEvent && $editingEvent->event_date
  ? \Illuminate\Support\Carbon::parse($editingEvent->event_date)->format('Y-m-d')
  : '';
  $editingBannerUrl = $editingEvent?->banner_url;
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

    .icon-btn.active {
      border-color: #23457e;
      color: #17396f;
      background: #eef4ff;
      box-shadow: inset 0 0 0 1px #23457e;
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

    .event-desc {
      display: none;
      margin: 8px 0 0;
      color: #5f7397;
      font-size: 13px;
      line-height: 1.5;
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

    .status.ended {
      color: #7e621c;
      background: #fff5df;
    }

    .status.archived {
      color: #8b1e2b;
      background: #fff3f4;
    }

    .manage {
      border: 0;
      background: transparent;
      color: #2b4f8d;
      font-size: 12px;
      font-weight: 700;
      text-decoration: underline;
      cursor: pointer;
      padding: 0;
      font-family: inherit;
      transition: color .2s ease;
    }

    .manage:hover {
      color: #17396f;
    }

    .events-list-head {
      display: none;
    }

    .event-list-row {
      display: none;
    }

    .grid.list-view {
      display: flex;
      flex-direction: column;
      gap: 8px;
    }

    .grid.list-view .events-list-head {
      display: grid;
      grid-template-columns: minmax(260px, 1.9fr) 130px minmax(220px, 1.2fr) 100px 90px;
      gap: 12px;
      align-items: center;
      padding: 2px 10px 6px;
      color: #6f7f9f;
      font-size: 11px;
      font-weight: 700;
      letter-spacing: .4px;
      text-transform: uppercase;
    }

    .grid.list-view .event-card {
      border-radius: 12px;
      box-shadow: 0 1px 3px rgba(15, 39, 80, .06);
      overflow: hidden;
    }

    .grid.list-view .event-image,
    .grid.list-view .event-body {
      display: none;
    }

    .grid.list-view .event-list-row {
      display: grid;
      grid-template-columns: minmax(260px, 1.9fr) 130px minmax(220px, 1.2fr) 100px 90px;
      gap: 12px;
      align-items: center;
      padding: 8px 10px;
      min-height: 70px;
    }

    .grid.list-view .event-desc {
      display: none;
    }

    .grid.list-view .event-footer {
      margin-top: 0;
      padding-top: 0;
      border-top: 0;
    }

    .list-col {
      min-width: 0;
    }

    .list-event-info {
      display: flex;
      align-items: center;
      gap: 10px;
      min-width: 0;
    }

    .list-thumb {
      width: 64px;
      height: 44px;
      border-radius: 8px;
      object-fit: cover;
      flex-shrink: 0;
      border: 1px solid #d9e2f1;
      display: block;
    }

    .list-event-title {
      margin: 0;
      color: #17396f;
      font-size: 14px;
      font-weight: 700;
      line-height: 1.3;
      overflow: hidden;
      text-overflow: ellipsis;
      white-space: nowrap;
    }

    .list-type {
      display: flex;
      align-items: center;
    }

    .list-type .badge {
      position: static;
      font-size: 10px;
      padding: 4px 9px;
      line-height: 1.1;
    }

    .list-date-location {
      display: grid;
      gap: 5px;
    }

    .list-meta {
      display: flex;
      align-items: center;
      gap: 6px;
      color: #5f7397;
      font-size: 12px;
      line-height: 1.2;
      min-width: 0;
    }

    .list-meta svg {
      width: 13px;
      height: 13px;
      stroke: #7a8ead;
      fill: none;
      stroke-width: 2;
      flex-shrink: 0;
    }

    .list-meta span {
      overflow: hidden;
      text-overflow: ellipsis;
      white-space: nowrap;
    }

    .list-status {
      display: flex;
      justify-content: flex-start;
      align-items: center;
    }

    .list-status .status {
      font-size: 10px;
      padding: 3px 7px;
      line-height: 1.1;
    }

    .list-action {
      display: flex;
      justify-content: flex-start;
      align-items: center;
    }

    .list-action .manage {
      text-decoration: none;
      font-size: 11px;
      font-weight: 700;
      border: 1.5px solid #d6e0f2;
      border-radius: 8px;
      color: #204985;
      background: #f6f9ff;
      padding: 5px 10px;
      line-height: 1;
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

      .grid.list-view .events-list-head {
        display: none;
      }

      .grid.list-view .event-card {
        border-radius: 12px;
      }

      .grid.list-view .event-list-row {
        grid-template-columns: 1fr;
        gap: 8px;
        align-items: start;
        padding: 10px;
      }

      .list-event-title {
        white-space: normal;
      }

      .list-action .manage {
        padding: 6px 10px;
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

    .footer-left {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .btn-archive {
      border: 2px solid #f4c7cb;
      background: #fff3f4;
      color: #8b1e2b;
      padding: 12px 20px;
      border-radius: 12px;
      font-weight: 800;
      cursor: pointer;
    }

    .current-banner-preview {
      margin-top: 10px;
      border: 1.5px solid #e6edf6;
      border-radius: 10px;
      background: #f9fbff;
      padding: 8px;
    }

    .current-banner-preview img {
      width: 100%;
      max-height: 220px;
      object-fit: cover;
      border-radius: 8px;
      display: block;
    }

    .current-banner-empty {
      margin-top: 10px;
      font-size: 12px;
      color: #7c8fae;
      border: 1.5px dashed #d9e3f1;
      border-radius: 10px;
      background: #f9fbff;
      padding: 12px;
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
          <input id="event-search-input" type="text" placeholder="Search events..." aria-label="Search events">
        </label>

        <button id="clear-filters-btn" class="icon-btn" type="button" title="Clear filters" aria-label="Clear filters">
          <svg viewBox="0 0 24 24">
            <polygon points="22 3 2 3 10 12 10 19 14 21 14 12 22 3"></polygon>
          </svg>
        </button>

        <select id="event-category-filter" class="filter-select" aria-label="Categories">
          <option value="">All Categories</option>
        </select>
        <select id="event-month-filter" class="filter-select" aria-label="Months">
          <option value="">All Months</option>
        </select>
        <div style="display:flex; gap:8px; align-items:center;">
          <select id="event-year-filter" class="filter-select" aria-label="Years" style="min-width:95px;">
            <option value="">All Years</option>
          </select>
          <button id="view-grid-btn" class="icon-btn active" type="button" title="Grid" aria-label="Grid view" aria-pressed="true">
            <svg viewBox="0 0 24 24">
              <rect x="4" y="4" width="6" height="6"></rect>
              <rect x="14" y="4" width="6" height="6"></rect>
              <rect x="4" y="14" width="6" height="6"></rect>
              <rect x="14" y="14" width="6" height="6"></rect>
            </svg>
          </button>
          <button id="view-list-btn" class="icon-btn" type="button" title="List" aria-label="List view" aria-pressed="false">
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
        <div class="events-list-head" aria-hidden="true">
          <span>Event Info</span>
          <span>Type</span>
          <span>Date &amp; Location</span>
          <span>Status</span>
          <span>Action</span>
        </div>
        @forelse ($events as $event)
        @php
        $imageClass = ['one', 'two', 'three'][$loop->index % 3];
        $eventDate = \Illuminate\Support\Carbon::parse($event->event_date);
        $eventMonth = (int) $eventDate->format('n');
        $eventYear = (int) $eventDate->format('Y');
        $isArchived = (string) $event->status === 'archived';
        $isActive = ! $isArchived && $eventDate->gte(now()->startOfDay());
        $statusLabel = $isArchived ? 'Archived' : ($isActive ? 'Active' : 'Ended');
        $statusClass = $isArchived ? 'archived' : ($isActive ? 'active' : 'ended');
        $bannerUrl = $event->banner_url;
        $listThumbnailUrl = $bannerUrl
        ?: 'https://images.unsplash.com/photo-1529156069898-49953e39b3ac?auto=format&fit=crop&w=640&q=60';
        $eventType = (string) $event->event_type === 'Conference' ? 'Conference' : 'School Event';
        $descriptionText = trim((string) ($event->description ?? ''));
        $shortDescription = $descriptionText !== ''
        ? \Illuminate\Support\Str::limit($descriptionText, 230)
        : 'No description available.';
        $eventPayload = [
        'event_id' => $event->event_id,
        'event_name' => $event->event_name,
        'event_type' => $event->event_type,
        'event_date' => $eventDate->format('Y-m-d'),
        'location' => $event->location,
        'description' => $event->description,
        'banner_url' => $event->banner_url,
        ];
        @endphp
        <article
          class="event-card"
          data-event-item="1"
          data-title="{{ $event->event_name }}"
          data-type="{{ $eventType }}"
          data-month="{{ $eventMonth }}"
          data-year="{{ $eventYear }}"
          data-location="{{ $event->location ?: 'TBA' }}"
          data-description="{{ $descriptionText }}">
          <div class="event-image {{ $bannerUrl ? '' : $imageClass }}" @if($bannerUrl) style="background-image: linear-gradient(160deg, rgba(18, 37, 73, .2), rgba(13, 31, 65, .42)), url('{{ $bannerUrl }}');" @endif>
            <span class="badge {{ $eventType === 'Conference' ? 'conference' : '' }}">{{ $eventType }}</span>
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
            <p class="event-desc">{{ $shortDescription }}</p>
            <div class="event-footer">
              <span class="status {{ $statusClass }}">{{ $statusLabel }}</span>
              <button
                type="button"
                class="manage manage-trigger"
                data-event='@json($eventPayload)'>
                Manage
              </button>
            </div>
          </div>

          <div class="event-list-row">
            <div class="list-col list-event-info">
              <img class="list-thumb" src="{{ $listThumbnailUrl }}" alt="{{ $event->event_name }}">
              <h3 class="list-event-title">{{ $event->event_name }}</h3>
            </div>

            <div class="list-col list-type">
              <span class="badge {{ $eventType === 'Conference' ? 'conference' : '' }}">{{ $eventType }}</span>
            </div>

            <div class="list-col list-date-location">
              <div class="list-meta">
                <svg viewBox="0 0 24 24">
                  <rect x="3" y="5" width="18" height="16" rx="2"></rect>
                  <line x1="8" y1="3" x2="8" y2="7"></line>
                  <line x1="16" y1="3" x2="16" y2="7"></line>
                </svg>
                <span>{{ $eventDate->format('Y-m-d') }}</span>
              </div>
              <div class="list-meta">
                <svg viewBox="0 0 24 24">
                  <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                  <circle cx="12" cy="10" r="3"></circle>
                </svg>
                <span>{{ $event->location ?: 'TBA' }}</span>
              </div>
            </div>

            <div class="list-col list-status">
              <span class="status {{ $statusClass }}">{{ $statusLabel }}</span>
            </div>

            <div class="list-col list-action">
              <button
                type="button"
                class="manage manage-trigger"
                data-event='@json($eventPayload)'>
                Manage
              </button>
            </div>
          </div>
        </article>
        @empty
        <article class="event-card events-empty-state" style="grid-column: 1 / -1;">
          <div class="event-body">
            <h3 class="event-title">No events yet</h3>
            <div class="meta">Create your first event using the button above.</div>
          </div>
        </article>
        @endforelse
        <article id="events-no-results" class="event-card events-empty-state" style="grid-column: 1 / -1; display:none;">
          <div class="event-body">
            <h3 class="event-title">No matching events</h3>
            <div class="meta">Try changing your search or filter selections.</div>
          </div>
        </article>
      </section>
    </main>
  </div>

  <!-- Create Event Modal -->
  <div id="event-modal-overlay" class="modal-overlay{{ $hasCreateErrors ? ' open' : '' }}" aria-hidden="{{ $hasCreateErrors ? 'false' : 'true' }}" data-auto-open="{{ $hasCreateErrors ? '1' : '0' }}">
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
          @if ($hasCreateErrors)
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

  <!-- Edit Event Modal -->
  <div id="edit-event-modal-overlay" class="modal-overlay{{ $hasEditErrors ? ' open' : '' }}" aria-hidden="{{ $hasEditErrors ? 'false' : 'true' }}" data-auto-open="{{ $hasEditErrors ? '1' : '0' }}">
    <div class="modal" role="dialog" aria-modal="true" aria-labelledby="edit-modal-title">
      <div class="modal-header">
        <div>
          <h2 id="edit-modal-title">Edit Event</h2>
          <p>Update event details, media, and publishing status.</p>
        </div>
        <button type="button" class="modal-close" aria-label="Close modal">✕</button>
      </div>

      <form id="edit-event-form" method="post" action="{{ $editingEventId > 0 ? route('admin.events.update', ['event' => $editingEventId]) : '' }}" enctype="multipart/form-data">
        @csrf
        @method('put')
        <input type="hidden" id="editing_event_id" name="editing_event_id" value="{{ old('editing_event_id', $editingEvent?->event_id) }}">

        <div class="modal-body">
          @if ($hasEditErrors)
          <div style="margin-bottom:14px; border:2px solid #f1b4ba; background:#fff3f4; color:#8b1e2b; border-radius:10px; padding:10px 12px; font-size:13px;">
            @foreach ($editErrorBag->all() as $error)
            <div>{{ $error }}</div>
            @endforeach
          </div>
          @endif

          <div class="card-panel">
            <h3>Basic Info</h3>

            <input type="hidden" name="event_type" id="edit_event_type" value="{{ old('event_type', $editingEvent?->event_type ?? 'School Event') }}">
            <div class="event-type-options" role="radiogroup" aria-label="Edit event type">
              <button type="button" class="event-type-option edit-event-type-option{{ old('event_type', $editingEvent?->event_type ?? 'School Event') === 'School Event' ? ' active' : '' }}" data-option="School Event">
                <strong>School Event</strong>
                <small>Regular campus activities</small>
              </button>
              <button type="button" class="event-type-option edit-event-type-option{{ old('event_type', $editingEvent?->event_type) === 'Conference' ? ' active' : '' }}" data-option="Conference">
                <strong>Conference</strong>
                <small>Conference sessions and presentations</small>
              </button>
            </div>

            <label style="display:block; font-weight:700; color:#233b6a; margin-bottom:8px;">Event Title</label>
            <input class="input" id="edit_event_name" type="text" name="event_name" value="{{ old('event_name', $editingEvent?->event_name) }}" placeholder="e.g. IT Week 2026" required>
          </div>

          <div class="card-panel">
            <h3>Schedule &amp; Location</h3>
            <div class="row">
              <div class="col">
                <label style="display:block; margin-bottom:8px; font-weight:700; color:#233b6a;">Date</label>
                <input class="input" id="edit_event_date" type="date" name="event_date" value="{{ old('event_date', $editingEventDate) }}" required>
              </div>
              <div class="col">
                <label style="display:block; margin-bottom:8px; font-weight:700; color:#233b6a;">Location / Venue</label>
                <input class="input" id="edit_location" type="text" name="location" value="{{ old('location', $editingEvent?->location) }}" placeholder="e.g. Main Auditorium">
              </div>
            </div>
          </div>

          <div class="card-panel">
            <h3>Content &amp; Media</h3>
            <label style="display:block; margin-bottom:8px; font-weight:700; color:#233b6a;">Full Description</label>
            <textarea class="input" id="edit_description" name="description" placeholder="Write detailed information...">{{ old('description', $editingEvent?->description) }}</textarea>

            <label style="display:block; margin:14px 0 8px; font-weight:700; color:#233b6a;">Event Banner Image</label>
            <div class="upload-input-wrap">
              <input class="upload-file-input" id="edit_banner_image" type="file" name="banner_image" accept="image/png,image/jpeg,image/jpg,image/webp">
              <label for="edit_banner_image" class="upload-file-trigger">Choose Image</label>
              <span class="upload-file-name" id="edit_banner_file_name" title="No file selected">No file selected</span>
            </div>
            <div class="field-note">Accepted formats: PNG, JPG, WEBP. Max size: 5 MB.</div>

            <div class="current-banner-preview" id="edit_banner_preview_wrap" @if (! $editingBannerUrl) style="display:none;" @endif>
              <img id="edit_banner_preview_image" src="{{ $editingBannerUrl ?? '' }}" alt="Current event banner">
            </div>
            <div class="current-banner-empty" id="edit_banner_empty" @if ($editingBannerUrl) style="display:none;" @endif>
              No banner image uploaded yet.
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <div class="footer-left">
            <button type="submit" form="archive-event-form" class="btn-archive">Archive Event</button>
            <button type="button" class="btn-cancel">Cancel</button>
          </div>
          <button type="submit" class="btn-publish">Save Changes</button>
        </div>
      </form>

      <form id="archive-event-form" method="post" action="{{ $editingEventId > 0 ? route('admin.events.archive', ['event' => $editingEventId]) : '' }}" style="display:none;">
        @csrf
        @method('patch')
      </form>
    </div>
  </div>

  <script>
    (function() {
      const createOpenBtn = document.querySelector('.create-btn');
      const manageButtons = document.querySelectorAll('.manage-trigger');
      const createOverlay = document.getElementById('event-modal-overlay');
      const editOverlay = document.getElementById('edit-event-modal-overlay');
      const updateRouteTemplate = "{{ route('admin.events.update', ['event' => '__EVENT__']) }}";
      const archiveRouteTemplate = "{{ route('admin.events.archive', ['event' => '__EVENT__']) }}";

      const createEventTypeInput = createOverlay ? createOverlay.querySelector('#event_type') : null;
      const createEventTypeButtons = createOverlay ? createOverlay.querySelectorAll('.event-type-option') : [];
      const createBannerInput = createOverlay ? createOverlay.querySelector('#banner_image') : null;
      const createBannerName = createOverlay ? createOverlay.querySelector('#banner_file_name') : null;

      const editForm = editOverlay ? editOverlay.querySelector('#edit-event-form') : null;
      const editArchiveForm = editOverlay ? editOverlay.querySelector('#archive-event-form') : null;
      const editIdInput = editOverlay ? editOverlay.querySelector('#editing_event_id') : null;
      const editNameInput = editOverlay ? editOverlay.querySelector('#edit_event_name') : null;
      const editTypeInput = editOverlay ? editOverlay.querySelector('#edit_event_type') : null;
      const editDateInput = editOverlay ? editOverlay.querySelector('#edit_event_date') : null;
      const editLocationInput = editOverlay ? editOverlay.querySelector('#edit_location') : null;
      const editDescriptionInput = editOverlay ? editOverlay.querySelector('#edit_description') : null;
      const editTypeButtons = editOverlay ? editOverlay.querySelectorAll('.edit-event-type-option') : [];
      const editBannerInput = editOverlay ? editOverlay.querySelector('#edit_banner_image') : null;
      const editBannerName = editOverlay ? editOverlay.querySelector('#edit_banner_file_name') : null;
      const editBannerPreviewWrap = editOverlay ? editOverlay.querySelector('#edit_banner_preview_wrap') : null;
      const editBannerPreviewImage = editOverlay ? editOverlay.querySelector('#edit_banner_preview_image') : null;
      const editBannerEmpty = editOverlay ? editOverlay.querySelector('#edit_banner_empty') : null;

      const eventsGrid = document.querySelector('.grid[aria-label="Events list"]');
      const eventCards = eventsGrid ? Array.from(eventsGrid.querySelectorAll('.event-card[data-event-item="1"]')) : [];
      const noResultsCard = document.getElementById('events-no-results');

      const searchInput = document.getElementById('event-search-input');
      const categoryFilter = document.getElementById('event-category-filter');
      const monthFilter = document.getElementById('event-month-filter');
      const yearFilter = document.getElementById('event-year-filter');
      const clearFiltersBtn = document.getElementById('clear-filters-btn');

      const viewGridBtn = document.getElementById('view-grid-btn');
      const viewListBtn = document.getElementById('view-list-btn');

      const monthLabels = {
        1: 'January',
        2: 'February',
        3: 'March',
        4: 'April',
        5: 'May',
        6: 'June',
        7: 'July',
        8: 'August',
        9: 'September',
        10: 'October',
        11: 'November',
        12: 'December',
      };

      function openOverlay(overlay) {
        if (!overlay) return;
        overlay.classList.add('open');
        overlay.setAttribute('aria-hidden', 'false');
        const first = overlay.querySelector('input, textarea, button');
        if (first) first.focus();
      }

      function closeOverlay(overlay) {
        if (!overlay) return;
        overlay.classList.remove('open');
        overlay.setAttribute('aria-hidden', 'true');
      }

      function bindOverlayDismiss(overlay, options = {}) {
        if (!overlay) return;

        const {
          allowCancel = true,
            allowOutsideClick = true,
        } = options;

        const closeButton = overlay.querySelector('.modal-close');
        const cancelButtons = overlay.querySelectorAll('.btn-cancel');

        if (closeButton) {
          closeButton.addEventListener('click', () => closeOverlay(overlay));
        }

        if (allowCancel) {
          cancelButtons.forEach((button) => {
            button.addEventListener('click', () => closeOverlay(overlay));
          });
        }

        if (allowOutsideClick) {
          overlay.addEventListener('click', (event) => {
            if (event.target === overlay) {
              closeOverlay(overlay);
            }
          });
        }
      }

      function setupEventTypeToggle(buttons, input) {
        if (!buttons || buttons.length === 0 || !input) return;

        buttons.forEach((button) => {
          button.addEventListener('click', () => {
            buttons.forEach((node) => node.classList.remove('active'));
            button.classList.add('active');
            input.value = button.dataset.option || 'School Event';
          });
        });
      }

      function setupBannerLabel(fileInput, fileNameNode) {
        if (!fileInput || !fileNameNode) return;

        fileInput.addEventListener('change', () => {
          const selected = fileInput.files && fileInput.files.length > 0 ?
            fileInput.files[0].name :
            'No file selected';
          fileNameNode.textContent = selected;
          fileNameNode.title = selected;
        });
      }

      function setEditBannerPreview(url) {
        const hasBanner = typeof url === 'string' && url.trim() !== '';

        if (editBannerPreviewWrap) {
          editBannerPreviewWrap.style.display = hasBanner ? '' : 'none';
        }

        if (editBannerPreviewImage) {
          editBannerPreviewImage.src = hasBanner ? url : '';
        }

        if (editBannerEmpty) {
          editBannerEmpty.style.display = hasBanner ? 'none' : '';
        }
      }

      function setEditFormActions(eventId) {
        const resolvedId = String(eventId || '').trim();
        if (resolvedId === '') return;

        if (editForm) {
          editForm.action = updateRouteTemplate.replace('__EVENT__', resolvedId);
        }

        if (editArchiveForm) {
          editArchiveForm.action = archiveRouteTemplate.replace('__EVENT__', resolvedId);
        }
      }

      function setEditTypeSelection(typeValue) {
        if (!editTypeInput || !editTypeButtons || editTypeButtons.length === 0) return;

        const resolvedType = typeValue === 'Conference' ? 'Conference' : 'School Event';
        editTypeInput.value = resolvedType;

        editTypeButtons.forEach((button) => {
          button.classList.toggle('active', button.dataset.option === resolvedType);
        });
      }

      function prefillEditForm(payload) {
        if (!payload || typeof payload !== 'object') return;

        if (editIdInput) {
          editIdInput.value = payload.event_id || '';
        }

        if (editNameInput) {
          editNameInput.value = payload.event_name || '';
        }

        if (editDateInput) {
          editDateInput.value = payload.event_date || '';
        }

        if (editLocationInput) {
          editLocationInput.value = payload.location || '';
        }

        if (editDescriptionInput) {
          editDescriptionInput.value = payload.description || '';
        }

        if (editBannerInput) {
          editBannerInput.value = '';
        }

        if (editBannerName) {
          editBannerName.textContent = 'No file selected';
          editBannerName.title = 'No file selected';
        }

        setEditTypeSelection(payload.event_type || 'School Event');
        setEditFormActions(payload.event_id || '');
        setEditBannerPreview(payload.banner_url || '');
      }

      function normalize(value) {
        return String(value || '').toLowerCase().trim();
      }

      function setViewMode(mode) {
        if (!eventsGrid) return;

        const isList = mode === 'list';
        eventsGrid.classList.toggle('list-view', isList);

        if (viewGridBtn) {
          viewGridBtn.classList.toggle('active', !isList);
          viewGridBtn.setAttribute('aria-pressed', !isList ? 'true' : 'false');
        }

        if (viewListBtn) {
          viewListBtn.classList.toggle('active', isList);
          viewListBtn.setAttribute('aria-pressed', isList ? 'true' : 'false');
        }
      }

      function populateFilterOptions() {
        if (!categoryFilter || !monthFilter || !yearFilter) return;

        const categories = [...new Set(eventCards
            .map((card) => String(card.dataset.type || '').trim())
            .filter((value) => value !== ''))]
          .sort((a, b) => a.localeCompare(b));

        const months = [...new Set(eventCards
            .map((card) => Number(card.dataset.month || 0))
            .filter((value) => Number.isInteger(value) && value >= 1 && value <= 12))]
          .sort((a, b) => a - b);

        const years = [...new Set(eventCards
            .map((card) => Number(card.dataset.year || 0))
            .filter((value) => Number.isInteger(value) && value > 0))]
          .sort((a, b) => b - a);

        categoryFilter.innerHTML = '<option value="">All Categories</option>';
        categories.forEach((category) => {
          const option = document.createElement('option');
          option.value = category;
          option.textContent = category;
          categoryFilter.appendChild(option);
        });

        monthFilter.innerHTML = '<option value="">All Months</option>';
        months.forEach((month) => {
          const option = document.createElement('option');
          option.value = String(month);
          option.textContent = monthLabels[month] || String(month);
          monthFilter.appendChild(option);
        });

        yearFilter.innerHTML = '<option value="">All Years</option>';
        years.forEach((year) => {
          const option = document.createElement('option');
          option.value = String(year);
          option.textContent = String(year);
          yearFilter.appendChild(option);
        });
      }

      function applyEventFilters() {
        if (!eventsGrid) return;

        const searchValue = normalize(searchInput ? searchInput.value : '');
        const selectedCategory = categoryFilter ? String(categoryFilter.value || '') : '';
        const selectedMonth = monthFilter ? String(monthFilter.value || '') : '';
        const selectedYear = yearFilter ? String(yearFilter.value || '') : '';

        let visibleCount = 0;

        eventCards.forEach((card) => {
          const matchesSearch = searchValue === '' ||
            normalize(card.dataset.title).includes(searchValue) ||
            normalize(card.dataset.location).includes(searchValue) ||
            normalize(card.dataset.description).includes(searchValue) ||
            normalize(card.dataset.type).includes(searchValue);

          const matchesCategory = selectedCategory === '' || String(card.dataset.type || '') === selectedCategory;
          const matchesMonth = selectedMonth === '' || String(card.dataset.month || '') === selectedMonth;
          const matchesYear = selectedYear === '' || String(card.dataset.year || '') === selectedYear;

          const isVisible = matchesSearch && matchesCategory && matchesMonth && matchesYear;
          card.style.display = isVisible ? '' : 'none';

          if (isVisible) {
            visibleCount += 1;
          }
        });

        if (noResultsCard) {
          const showNoResults = eventCards.length > 0 && visibleCount === 0;
          noResultsCard.style.display = showNoResults ? '' : 'none';
        }
      }

      if (createOpenBtn) {
        createOpenBtn.addEventListener('click', () => openOverlay(createOverlay));
      }

      manageButtons.forEach((button) => {
        button.addEventListener('click', () => {
          let payload = null;

          try {
            payload = JSON.parse(button.dataset.event || '{}');
          } catch (_error) {
            payload = null;
          }

          if (!payload || !payload.event_id) {
            return;
          }

          prefillEditForm(payload);
          openOverlay(editOverlay);
        });
      });

      if (editArchiveForm) {
        editArchiveForm.addEventListener('submit', (event) => {
          const confirmed = window.confirm('Are you sure you want to archive this event? It will no longer be visible on the landing page.');
          if (!confirmed) {
            event.preventDefault();
          }
        });
      }

      if (searchInput) {
        searchInput.addEventListener('input', applyEventFilters);
      }

      if (categoryFilter) {
        categoryFilter.addEventListener('change', applyEventFilters);
      }

      if (monthFilter) {
        monthFilter.addEventListener('change', applyEventFilters);
      }

      if (yearFilter) {
        yearFilter.addEventListener('change', applyEventFilters);
      }

      if (clearFiltersBtn) {
        clearFiltersBtn.addEventListener('click', () => {
          if (searchInput) searchInput.value = '';
          if (categoryFilter) categoryFilter.value = '';
          if (monthFilter) monthFilter.value = '';
          if (yearFilter) yearFilter.value = '';
          applyEventFilters();
        });
      }

      if (viewGridBtn) {
        viewGridBtn.addEventListener('click', () => setViewMode('grid'));
      }

      if (viewListBtn) {
        viewListBtn.addEventListener('click', () => setViewMode('list'));
      }

      setupEventTypeToggle(createEventTypeButtons, createEventTypeInput);
      setupEventTypeToggle(editTypeButtons, editTypeInput);
      setupBannerLabel(createBannerInput, createBannerName);
      setupBannerLabel(editBannerInput, editBannerName);
      populateFilterOptions();
      setViewMode('grid');
      applyEventFilters();
      bindOverlayDismiss(createOverlay, {
        allowCancel: false,
        allowOutsideClick: false,
      });
      bindOverlayDismiss(editOverlay, {
        allowCancel: false,
        allowOutsideClick: false,
      });

      if (createOverlay && createOverlay.dataset.autoOpen === '1') {
        openOverlay(createOverlay);
      }

      if (editOverlay && editOverlay.dataset.autoOpen === '1') {
        openOverlay(editOverlay);
      }

      document.addEventListener('keydown', (event) => {
        if (event.key !== 'Escape') return;

        if (editOverlay && editOverlay.classList.contains('open')) {
          return;
        }

        if (createOverlay && createOverlay.classList.contains('open')) {
          return;
        }
      });
    })();
  </script>
</body>

</html>