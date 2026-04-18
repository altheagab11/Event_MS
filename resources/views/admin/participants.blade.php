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
      font-size: 29px;
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

    .details-btn {
      border: 0;
      background: #f4f6fa;
      color: #4e709f;
      border-radius: 10px;
      padding: 8px 14px;
      font-size: 12px;
      font-weight: 700;
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
              <tr>
                <td>
                  <div class="name">Maria Santos</div>
                  <div class="email">msantos@national-u.edu.ph</div>
                </td>
                <td><span class="event">NU Lipa Foundation Day 2026</span></td>
                <td><span class="chip green">Checked-in</span></td>
                <td><button class="details-btn" type="button">View Details</button></td>
              </tr>
              <tr>
                <td>
                  <div class="name">John Doe</div>
                  <div class="email">jdoe@student.edu.ph</div>
                </td>
                <td><span class="event">Tech Innovations Summit</span></td>
                <td><span class="chip blue">Registered</span></td>
                <td><button class="details-btn" type="button">View Details</button></td>
              </tr>
              <tr>
                <td>
                  <div class="name">Anna Reyes</div>
                  <div class="email">areyes@national.edu.ph</div>
                </td>
                <td><span class="event">NU Lipa Foundation Day 2026</span></td>
                <td><span class="chip blue">Registered</span></td>
                <td><button class="details-btn" type="button">View Details</button></td>
              </tr>
              <tr>
                <td>
                  <div class="name">Mark Cruz</div>
                  <div class="email">mcruz@up.edu.ph</div>
                </td>
                <td><span class="event">Tech Innovations Summit</span></td>
                <td><span class="chip gold">Pending Paper</span></td>
                <td><button class="details-btn" type="button">View Details</button></td>
              </tr>
            </tbody>
          </table>
        </div>
      </section>
    </main>
  </div>
</body>

</html>