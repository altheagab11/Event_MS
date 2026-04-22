<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Participants | NU Lipa EMS</title>
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
      font-size: 15px;
      font-weight: 500;
    }

    .table-card {
      background: #fff;
      border: 2px solid #e1e5ed;
      border-radius: 14px;
      overflow: hidden;
      box-shadow: 0 1px 4px rgba(15, 39, 80, .05);
    }

    .table-toolbar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      gap: 12px;
      padding: 14px 14px;
      border-bottom: 1px solid #edf0f5;
    }

    .toolbar-title {
      margin: 0;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      font-size: 12px;
      color: #15396f;
      font-weight: 700;
    }

    .toolbar-title svg {
      width: 16px;
      height: 16px;
      stroke: currentColor;
      fill: none;
      stroke-width: 2;
    }

    .toolbar-select {
      min-width: 140px;
      border: 1px solid #e0e5ee;
      background: #f8fafc;
      border-radius: 10px;
      height: 32px;
      padding: 0 10px;
      font-family: inherit;
      font-size: 12px;
      color: #3f537a;
      font-weight: 600;
    }

    table {
      width: 100%;
      border-collapse: collapse;
    }

    thead th {
      text-align: left;
      padding: 12px 14px;
      font-size: 12px;
      color: #546a8f;
      background: #f5f7fb;
      font-weight: 700;
    }

    thead th:last-child,
    tbody td:last-child {
      text-align: right;
    }

    tbody td {
      padding: 12px 14px;
      border-top: 1px solid #edf1f6;
      vertical-align: middle;
    }

    .name {
      font-size: 13px;
      color: #17396f;
      font-weight: 700;
      margin-bottom: 2px;
    }

    .email {
      font-size: 11px;
      color: #7a8ca8;
    }

    .event {
      font-size: 13px;
      color: #1c3f73;
      font-weight: 600;
    }

    .chip {
      display: inline-block;
      border-radius: 999px;
      padding: 4px 10px;
      font-size: 11px;
      font-weight: 700;
      line-height: 1;
    }

    .chip.green {
      color: #1a9950;
      background: #e2f6e9;
    }

    .chip.blue {
      color: #2d69e5;
      background: #e9f0ff;
    }

    .chip.gold {
      color: #a67500;
      background: #fff1bd;
    }

    .chip.red {
      color: #b4232a;
      background: #ffe7e9;
    }

    .details-btn {
      border: 0;
      background: #f4f6fa;
      color: #4e709f;
      border-radius: 10px;
      padding: 8px 14px;
      font-size: 12px;
      font-weight: 700;
      cursor: pointer;
    }

    .flash-message {
      border-radius: 12px;
      padding: 10px 14px;
      font-size: 13px;
      font-weight: 700;
      margin: 0 0 14px;
    }

    .flash-message.success {
      border: 1px solid #b8e3c9;
      background: #edfff3;
      color: #1d6c3e;
    }

    .flash-message.warning {
      border: 1px solid #f1d49a;
      background: #fff7e8;
      color: #8d5d00;
    }

    .modal-backdrop {
      position: fixed;
      inset: 0;
      background: rgba(15, 30, 57, .48);
      display: none;
      align-items: center;
      justify-content: center;
      z-index: 40;
      padding: 18px;
    }

    .modal-backdrop.open {
      display: flex;
    }

    .participant-modal {
      width: min(780px, 100%);
      background: linear-gradient(180deg, #ffffff 0%, #f6f9ff 100%);
      border: 1px solid #d8e2f1;
      border-radius: 22px;
      box-shadow: 0 22px 52px rgba(16, 37, 72, .24);
      overflow: hidden;
    }

    .modal-head {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      gap: 14px;
      padding: 18px 20px 16px;
      background: linear-gradient(135deg, #163f7d 0%, #1f4f95 58%, #2f67b3 100%);
      color: #fff;
      border-bottom: 1px solid rgba(255, 255, 255, .16);
    }

    .modal-head-main {
      display: grid;
      gap: 8px;
      min-width: 0;
    }

    .modal-title {
      margin: 0;
      font-size: 22px;
      color: #fff;
      font-weight: 800;
      letter-spacing: .2px;
      line-height: 1.15;
    }

    .modal-event-title {
      margin: 0;
      font-size: 14px;
      color: rgba(255, 255, 255, .95);
      font-weight: 600;
      line-height: 1.4;
      word-break: break-word;
    }

    .modal-participant-name {
      margin: 0;
      font-size: 14px;
      color: rgba(255, 255, 255, .98);
      font-weight: 700;
      line-height: 1.3;
      word-break: break-word;
    }

    .modal-status-pill {
      display: inline-flex;
      align-items: center;
      width: fit-content;
      border-radius: 999px;
      padding: 5px 10px;
      font-size: 11px;
      letter-spacing: .4px;
      text-transform: uppercase;
      font-weight: 800;
      background: rgba(255, 255, 255, .18);
      border: 1px solid rgba(255, 255, 255, .25);
      color: #fff;
    }

    .modal-close {
      border: 1px solid rgba(255, 255, 255, .35);
      width: 34px;
      height: 34px;
      border-radius: 10px;
      background: rgba(255, 255, 255, .16);
      color: #fff;
      font-size: 22px;
      line-height: 1;
      cursor: pointer;
      flex: 0 0 auto;
    }

    .modal-close:hover {
      background: rgba(255, 255, 255, .24);
    }

    .modal-body {
      padding: 18px 20px 20px;
      display: grid;
      gap: 16px;
      background: transparent;
    }

    .detail-grid {
      display: grid;
      grid-template-columns: repeat(2, minmax(0, 1fr));
      gap: 12px;
      border: 1px solid #deebfb;
      border-radius: 16px;
      background: #ffffff;
      padding: 14px;
      box-shadow: inset 0 0 0 1px #eef4ff;
    }

    .detail-item {
      border: 1px solid #e7eef9;
      border-radius: 12px;
      background: #f9fbff;
      padding: 11px 12px;
    }

    .detail-item.full {
      grid-column: 1 / -1;
    }

    .detail-label {
      margin: 0 0 4px;
      font-size: 10px;
      letter-spacing: .7px;
      text-transform: uppercase;
      color: #7f93b2;
      font-weight: 800;
    }

    .detail-value {
      margin: 0;
      font-size: 14px;
      color: #1a3d74;
      font-weight: 700;
      word-break: break-word;
      line-height: 1.35;
    }

    .paper-section {
      border: 1px solid #d7e4f8;
      border-radius: 16px;
      background: linear-gradient(180deg, #f9fbff 0%, #f1f6ff 100%);
      padding: 14px;
      display: grid;
      gap: 10px;
    }

    .paper-title-wrap {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .paper-title-icon {
      width: 30px;
      height: 30px;
      border-radius: 9px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      background: #e8f0ff;
      color: #1f4f95;
      flex: 0 0 auto;
    }

    .paper-title-icon svg {
      width: 16px;
      height: 16px;
      fill: none;
      stroke: currentColor;
      stroke-width: 2;
      stroke-linecap: round;
      stroke-linejoin: round;
    }

    .paper-title {
      margin: 0;
      font-size: 14px;
      color: #183d7e;
      font-weight: 800;
    }

    .paper-subtitle {
      margin: 2px 0 0;
      font-size: 12px;
      color: #6f84a6;
      font-weight: 600;
    }

    .paper-file-card {
      display: flex;
      gap: 10px;
      align-items: flex-start;
      justify-content: space-between;
      border: 1px solid #d9e7fb;
      border-radius: 12px;
      background: #fff;
      padding: 10px;
    }

    .paper-file-main {
      display: grid;
      gap: 8px;
      min-width: 0;
      flex: 1 1 auto;
    }

    .paper-file-name {
      margin: 0;
      font-size: 13px;
      font-weight: 800;
      color: #1b3f75;
      word-break: break-word;
      line-height: 1.35;
    }

    .paper-meta {
      display: flex;
      flex-wrap: wrap;
      gap: 6px;
      margin: 0;
    }

    .paper-chip {
      display: inline-flex;
      align-items: center;
      border-radius: 999px;
      padding: 4px 8px;
      font-size: 11px;
      font-weight: 700;
      color: #2f4f82;
      background: #eef3fc;
      border: 1px solid #d9e4f4;
    }

    .paper-details-grid {
      display: grid;
      grid-template-columns: repeat(2, minmax(0, 1fr));
      gap: 8px;
    }

    .paper-row {
      display: grid;
      gap: 2px;
      font-size: 12px;
      padding: 8px;
      border: 1px solid #e8eef8;
      border-radius: 10px;
      background: #fafcff;
    }

    .paper-row span:first-child {
      color: #6f84a6;
      font-weight: 700;
      font-size: 10px;
      letter-spacing: .45px;
      text-transform: uppercase;
    }

    .paper-row span:last-child {
      color: #173b72;
      font-weight: 700;
      word-break: break-word;
    }

    .download-btn {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      background: #1e4f95;
      color: #fff;
      border-radius: 10px;
      padding: 10px 12px;
      text-decoration: none;
      font-size: 12px;
      font-weight: 800;
      white-space: nowrap;
      border: 1px solid #184587;
      align-self: center;
    }

    .download-btn:hover {
      background: #1a4482;
    }

    .paper-empty {
      border: 1px dashed #cddcef;
      border-radius: 12px;
      background: #fff;
      color: #6d83a5;
      padding: 14px;
      font-size: 13px;
      font-weight: 600;
    }

    .review-actions {
      border: 1px solid #dce8fb;
      border-radius: 16px;
      background: #ffffff;
      padding: 14px;
      display: grid;
      gap: 10px;
      box-shadow: inset 0 0 0 1px #eef4ff;
    }

    .review-actions-title {
      margin: 0;
      font-size: 13px;
      color: #173c74;
      font-weight: 800;
    }

    .review-actions-note {
      margin: 0;
      font-size: 12px;
      color: #6e84a6;
      line-height: 1.45;
      font-weight: 600;
    }

    .review-actions-row {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 10px;
    }

    .review-actions-row form {
      margin: 0;
    }

    .review-btn {
      width: 100%;
      border-radius: 11px;
      padding: 11px 14px;
      font-size: 13px;
      font-weight: 800;
      cursor: pointer;
      font-family: inherit;
      transition: transform .12s ease, box-shadow .12s ease;
    }

    .review-btn.reject {
      background: #fff;
      color: #b3263c;
      border: 1px solid #e5a0ac;
      box-shadow: inset 0 0 0 1px #f6d3d9;
    }

    .review-btn.approve {
      background: #1e4f95;
      color: #fff;
      border: 1px solid #174486;
      box-shadow: 0 5px 14px rgba(23, 68, 134, .2);
    }

    .review-btn:hover:not(:disabled) {
      transform: translateY(-1px);
    }

    .review-btn:disabled {
      opacity: .65;
      cursor: not-allowed;
    }

    @media (max-width: 1060px) {
      .layout {
        grid-template-columns: 1fr;
      }

      .sidebar {
        flex-direction: row;
        align-items: center;
        gap: 14px;
        border-right: 0;
        border-bottom: 1px solid #e3e5ef;
        padding: 14px;
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

      .page-title {
        font-size: 28px;
      }

      .page-subtitle {
        font-size: 14px;
      }

      .table-toolbar {
        flex-direction: column;
        align-items: stretch;
      }

      .toolbar-select {
        width: 100%;
      }

      .table-wrap {
        overflow-x: auto;
      }

      table {
        min-width: 780px;
      }

      .participant-modal {
        border-radius: 18px;
      }

      .modal-head {
        padding: 16px;
      }

      .modal-title {
        font-size: 20px;
      }

      .modal-body {
        padding: 14px;
      }

      .detail-grid {
        grid-template-columns: 1fr;
      }

      .paper-file-card {
        flex-direction: column;
      }

      .paper-details-grid {
        grid-template-columns: 1fr;
      }

      .download-btn {
        width: 100%;
      }

      .review-actions-row {
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
          <a href="{{ route('admin.events') }}">
            <svg viewBox="0 0 24 24">
              <rect x="3" y="5" width="18" height="16" rx="2"></rect>
              <line x1="8" y1="3" x2="8" y2="7"></line>
              <line x1="16" y1="3" x2="16" y2="7"></line>
            </svg>
            Events
          </a>
          <a href="{{ route('admin.participants') }}" class="active">
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
      <h1 class="page-title">Participants List</h1>
      <p class="page-subtitle">View registered attendees, approve papers, and monitor check-ins.</p>

      @if (session('status_message'))
        <div class="flash-message {{ session('status_type') === 'warning' ? 'warning' : 'success' }}" role="status">
          {{ session('status_message') }}
        </div>
      @endif

      <section class="table-card" aria-label="Participants table">
        <div class="table-toolbar">
          <h2 class="toolbar-title">
            <svg viewBox="0 0 24 24">
              <circle cx="9" cy="8" r="3"></circle>
              <path d="M2.5 19c1.1-2.7 3.4-4 6.5-4"></path>
              <circle cx="17" cy="9" r="2.5"></circle>
              <path d="M14.5 18.5c.8-1.8 2.2-2.8 4.2-2.8"></path>
            </svg>
            All Registrations
          </h2>
          <select class="toolbar-select" aria-label="Filter by event">
            <option>All Events</option>
          </select>
        </div>

        <div class="table-wrap">
          <table>
            <thead>
              <tr>
                <th>Participant Name</th>
                <th>Registered Event</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($participants as $participant)
                <tr>
                  <td>
                    <div class="name">{{ $participant['name'] }}</div>
                    <div class="email">{{ $participant['email'] }}</div>
                  </td>
                  <td><span class="event">{{ $participant['event_name'] }}</span></td>
                  <td><span class="chip {{ $participant['status_class'] }}">{{ $participant['status_label'] }}</span></td>
                  <td><button class="details-btn" type="button" data-registration-id="{{ $participant['registration_id'] }}">View Details</button></td>
                </tr>
              @empty
                <tr>
                  <td colspan="4">
                    <div class="email">No participant registrations found yet.</div>
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </section>
    </main>
  </div>

  <div class="modal-backdrop" id="participantDetailsModal" aria-hidden="true">
    <div class="participant-modal" role="dialog" aria-modal="true" aria-labelledby="participantDetailsTitle">
      <div class="modal-head">
        <div class="modal-head-main">
          <h2 class="modal-title" id="participantDetailsTitle">Participant Details</h2>
          <p class="modal-participant-name" id="detailHeaderParticipant">-</p>
          <p class="modal-event-title" id="detailEventTitle">-</p>
          <span class="modal-status-pill" id="detailRegistrationStatus">-</span>
        </div>
        <button type="button" class="modal-close" id="participantDetailsClose" aria-label="Close">&times;</button>
      </div>
      <div class="modal-body">
        <div class="detail-grid">
          <div class="detail-item">
            <p class="detail-label">Full Name</p>
            <p class="detail-value" id="detailFullName">-</p>
          </div>
          <div class="detail-item">
            <p class="detail-label">Email Address</p>
            <p class="detail-value" id="detailEmail">-</p>
          </div>
          <div class="detail-item">
            <p class="detail-label">School / Affiliation</p>
            <p class="detail-value" id="detailSchool">-</p>
          </div>
          <div class="detail-item">
            <p class="detail-label">Level / Region</p>
            <p class="detail-value" id="detailLevelRegion">-</p>
          </div>
          <div class="detail-item full">
            <p class="detail-label">Registration Status</p>
            <p class="detail-value" id="detailRegistrationStatusValue">-</p>
          </div>
        </div>

        <section class="paper-section" id="paperSection" aria-label="Research paper submission">
          <div class="paper-title-wrap">
            <span class="paper-title-icon" aria-hidden="true">
              <svg viewBox="0 0 24 24">
                <path d="M14 2H7a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7z"></path>
                <path d="M14 2v5h5"></path>
                <path d="M9 13h6"></path>
                <path d="M9 17h4"></path>
              </svg>
            </span>
            <div>
              <h3 class="paper-title">Research Paper Submission</h3>
              <p class="paper-subtitle">Conference application file under review</p>
            </div>
          </div>

          <div id="paperPresent" style="display:none;">
            <div class="paper-file-card">
              <div class="paper-file-main">
                <p class="paper-file-name" id="detailPaperName">-</p>
                <div class="paper-meta">
                  <span class="paper-chip" id="detailPaperType">-</span>
                  <span class="paper-chip" id="detailPaperSize">-</span>
                  <span class="paper-chip" id="detailPaperStatus">-</span>
                </div>

                <div class="paper-details-grid">
                  <div class="paper-row">
                    <span>Submission Status</span>
                    <span id="detailPaperStatusLabel">-</span>
                  </div>
                  <div class="paper-row">
                    <span>Submitted At</span>
                    <span id="detailPaperSubmittedAt">-</span>
                  </div>
                </div>
              </div>
              <a id="detailPaperDownload" class="download-btn" href="#">Download Paper</a>
            </div>
          </div>

          <div id="paperEmpty" class="paper-empty" style="display:none;">
            No research paper has been uploaded for this participant yet.
          </div>
        </section>

        <section class="review-actions" id="reviewActions" style="display:none;" aria-label="Application review actions">
          <h3 class="review-actions-title">Application Review</h3>
          <p class="review-actions-note">Use these actions to approve or reject this pending registration.</p>
          <div class="review-actions-row">
            <form id="rejectApplicationForm" method="POST" action="#">
              @csrf
              <button class="review-btn reject" id="rejectApplicationBtn" type="submit">Reject Application</button>
            </form>
            <form id="approveApplicationForm" method="POST" action="#">
              @csrf
              <button class="review-btn approve" id="approveApplicationBtn" type="submit">Approve &amp; Send ID</button>
            </form>
          </div>
        </section>
      </div>
    </div>
  </div>

  <script>
    (function() {
      const participantRows = @json($participants->values());
      const participantMap = new Map(participantRows.map(row => [String(row.registration_id), row]));

      const modal = document.getElementById('participantDetailsModal');
      const closeBtn = document.getElementById('participantDetailsClose');
      const detailsButtons = Array.from(document.querySelectorAll('.details-btn'));

      const detailEventTitle = document.getElementById('detailEventTitle');
      const detailHeaderParticipant = document.getElementById('detailHeaderParticipant');
      const detailFullName = document.getElementById('detailFullName');
      const detailEmail = document.getElementById('detailEmail');
      const detailSchool = document.getElementById('detailSchool');
      const detailLevelRegion = document.getElementById('detailLevelRegion');
      const detailRegistrationStatus = document.getElementById('detailRegistrationStatus');
      const detailRegistrationStatusValue = document.getElementById('detailRegistrationStatusValue');

      const paperPresent = document.getElementById('paperPresent');
      const paperEmpty = document.getElementById('paperEmpty');
      const paperSection = document.getElementById('paperSection');
      const detailPaperName = document.getElementById('detailPaperName');
      const detailPaperType = document.getElementById('detailPaperType');
      const detailPaperSize = document.getElementById('detailPaperSize');
      const detailPaperStatus = document.getElementById('detailPaperStatus');
      const detailPaperStatusLabel = document.getElementById('detailPaperStatusLabel');
      const detailPaperSubmittedAt = document.getElementById('detailPaperSubmittedAt');
      const detailPaperDownload = document.getElementById('detailPaperDownload');
      const reviewActions = document.getElementById('reviewActions');
      const rejectApplicationForm = document.getElementById('rejectApplicationForm');
      const approveApplicationForm = document.getElementById('approveApplicationForm');
      const rejectApplicationBtn = document.getElementById('rejectApplicationBtn');
      const approveApplicationBtn = document.getElementById('approveApplicationBtn');

      function normalizeText(value, fallback = 'Not provided') {
        const text = String(value ?? '').trim();
        return text !== '' ? text : fallback;
      }

      function formatPaperStatus(value) {
        const status = normalizeText(value, 'Unknown');
        return status
          .replaceAll('_', ' ')
          .split(' ')
          .map(token => token ? (token.charAt(0).toUpperCase() + token.slice(1).toLowerCase()) : '')
          .join(' ');
      }

      function formatRegistrationStatus(value) {
        return formatPaperStatus(value);
      }

      function openDetails(registrationId) {
        const participant = participantMap.get(String(registrationId));
        if (!participant) {
          return;
        }

        detailEventTitle.textContent = normalizeText(participant.event_name, 'Unknown Event');
        detailFullName.textContent = normalizeText(participant.name, 'Unknown Participant');
        detailHeaderParticipant.textContent = normalizeText(participant.name, 'Unknown Participant');
        detailEmail.textContent = normalizeText(participant.email, 'Not provided');
        detailSchool.textContent = normalizeText(participant.school_affiliation, 'Not provided');
        detailLevelRegion.textContent = normalizeText(participant.level_region, 'Not provided');
        const formattedRegistrationStatus = formatRegistrationStatus(participant.registration_status || 'Unknown');
        detailRegistrationStatus.textContent = formattedRegistrationStatus;
        detailRegistrationStatusValue.textContent = formattedRegistrationStatus;

        const isConference = String(participant.event_type || '').trim() === 'Conference';
        modal.classList.toggle('conference-mode', isConference);
        paperSection.style.display = isConference ? '' : 'none';

        if (!isConference) {
          paperPresent.style.display = 'none';
          paperEmpty.style.display = 'none';
        }

        const paper = participant.paper || {};
        if (isConference && paper.has_file) {
          paperPresent.style.display = '';
          paperEmpty.style.display = 'none';

          detailPaperName.textContent = normalizeText(paper.file_name, 'Unknown file');
          detailPaperType.textContent = normalizeText(paper.file_type, 'Unknown');
          detailPaperSize.textContent = normalizeText(paper.file_size, 'Not available');
          const formattedPaperStatus = formatPaperStatus(paper.status);
          detailPaperStatus.textContent = formattedPaperStatus;
          detailPaperStatusLabel.textContent = formattedPaperStatus;
          detailPaperSubmittedAt.textContent = normalizeText(paper.submitted_at, 'Not available');

          const downloadUrl = normalizeText(paper.download_url, '');
          detailPaperDownload.href = downloadUrl;
          detailPaperDownload.style.pointerEvents = downloadUrl !== '' ? 'auto' : 'none';
          detailPaperDownload.style.opacity = downloadUrl !== '' ? '1' : '.65';
        } else if (isConference) {
          paperPresent.style.display = 'none';
          paperEmpty.style.display = '';
          detailPaperDownload.removeAttribute('href');
        }

        const registrationStatus = String(participant.registration_status || '').trim().toLowerCase();
        const canReview = registrationStatus === 'pending' && (!isConference || Boolean(paper.has_file));

        reviewActions.style.display = canReview ? '' : 'none';
        if (canReview) {
          const rejectUrl = normalizeText(participant.reject_url, '');
          const approveUrl = normalizeText(participant.approve_url, '');
          rejectApplicationForm.action = rejectUrl;
          approveApplicationForm.action = approveUrl;
          rejectApplicationBtn.disabled = rejectUrl === '';
          approveApplicationBtn.disabled = approveUrl === '';
        } else {
          rejectApplicationForm.action = '#';
          approveApplicationForm.action = '#';
          rejectApplicationBtn.disabled = true;
          approveApplicationBtn.disabled = true;
        }

        modal.classList.add('open');
        modal.setAttribute('aria-hidden', 'false');
      }

      function closeModal() {
        modal.classList.remove('open');
        modal.setAttribute('aria-hidden', 'true');
      }

      detailsButtons.forEach(button => {
        button.addEventListener('click', () => openDetails(button.dataset.registrationId));
      });

      rejectApplicationForm.addEventListener('submit', (event) => {
        const shouldProceed = window.confirm('Reject this application? This will mark the registration as rejected.');
        if (!shouldProceed) {
          event.preventDefault();
        }
      });

      approveApplicationForm.addEventListener('submit', (event) => {
        const shouldProceed = window.confirm('Approve this application and send the digital ID email now?');
        if (!shouldProceed) {
          event.preventDefault();
        }
      });

      closeBtn.addEventListener('click', closeModal);
      modal.addEventListener('click', (event) => {
        if (event.target === modal) {
          closeModal();
        }
      });

      document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && modal.classList.contains('open')) {
          closeModal();
        }
      });
    })();
  </script>
</body>

</html>