<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard | NU Lipa EMS</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <style>
    :root {
      --blue-900: #183d7e;
      --blue-800: #224381;
      --blue-700: #2f5b8f;
      --gold: #f5c36c;
      --gold-soft: #f2d3a8;
      --ink: #12356b;
      --bg: #f2f3f8;
      --card: #ffffff;
      --line: #23457e;
      --text: #1a3462;
      --muted: #6f7f9f;
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
      padding: 20px 22px;
    }

    .topbar {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      margin-bottom: 16px;
      gap: 12px;
    }

    h1,
    .page-title {
      margin: 0;
      color: var(--ink);
      font-size: 42px;
      line-height: 1.04;
      font-weight: 800;
      letter-spacing: .2px;
    }

    .subtitle {
      margin-top: 6px;
      color: #7281a0;
      font-size: 16px;
      font-weight: 500;
    }

    .scan-btn {
      border: 3px solid var(--line);
      border-radius: 16px;
      background: var(--gold);
      color: var(--ink);
      font-weight: 800;
      letter-spacing: .5px;
      font-size: 14px;
      padding: 12px 18px;
      display: inline-flex;
      align-items: center;
      gap: 10px;
      box-shadow: 4px 4px 0 0 #213d76;
    }

    .scan-btn svg {
      width: 16px;
      height: 16px;
      stroke: currentColor;
      fill: none;
      stroke-width: 2;
    }

    .stats {
      display: grid;
      grid-template-columns: repeat(4, minmax(0, 1fr));
      gap: 14px;
      margin-bottom: 16px;
    }

    .card {
      background: var(--card);
      border: 4px solid var(--line);
      border-radius: var(--radius);
      box-shadow: var(--shadow);
      padding: 16px 18px;
    }

    .stat-card {
      min-height: 114px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .stat-card.peach {
      background: #f1d7b2;
    }

    .stat-card.gold {
      background: var(--gold);
    }

    .stat-card.blue {
      background: #5d7d9a;
      color: #fff;
    }

    .stat-card.white {
      background: #fff;
    }

    .label {
      font-size: 11px;
      font-weight: 700;
      letter-spacing: .7px;
    }

    .value {
      margin-top: 8px;
      font-size: 39px;
      font-weight: 800;
      line-height: 1;
    }

    .icon-box {
      width: 50px;
      height: 50px;
      border: 3px solid var(--line);
      border-radius: 12px;
      display: grid;
      place-items: center;
      background: #f6ca82;
    }

    .stat-card.blue .icon-box {
      border-color: #23457e;
      background: #1d3d78;
    }

    .stat-card.white .icon-box {
      background: #6a84a0;
    }

    .icon-box svg {
      width: 21px;
      height: 21px;
      stroke: #16386d;
      fill: none;
      stroke-width: 2;
    }

    .stat-card.white .icon-box svg,
    .stat-card.blue .icon-box svg {
      stroke: #fff;
    }

    .grid {
      display: grid;
      grid-template-columns: 1.06fr 1fr;
      gap: 14px;
      margin-bottom: 16px;
    }

    .section-title {
      margin: 0 0 16px;
      font-size: 34px;
      font-weight: 800;
      letter-spacing: .3px;
      color: var(--ink);
    }

    .demographic {
      display: grid;
      gap: 20px;
      margin-top: 10px;
    }

    .demographic-row {
      display: grid;
      gap: 10px;
    }

    .demographic-head {
      display: flex;
      justify-content: space-between;
      color: var(--ink);
      font-size: 18px;
      font-weight: 700;
    }

    .track {
      background: #f6ecd8;
      border: 3px solid #23457e;
      border-radius: 999px;
      height: 18px;
      position: relative;
      overflow: hidden;
    }

    .fill {
      height: 100%;
      background: #1f4284;
    }

    .fill.gold {
      background: #f0b95f;
    }

    .fill.gray {
      background: #6b87a4;
    }

    .chart-card {
      background: #f5c36c;
    }

    .chart-wrap {
      display: grid;
      place-items: center;
      padding-top: 12px;
    }

    .ring {
      width: 210px;
      height: 210px;
      border-radius: 50%;
      background: conic-gradient(#1f4284 75%, #efdec4 0);
      position: relative;
      display: grid;
      place-items: center;
      margin-bottom: 18px;
    }

    .ring::before {
      content: "";
      position: absolute;
      width: 142px;
      height: 142px;
      border-radius: 50%;
      background: #f5c36c;
      border: 8px solid #1f4284;
    }

    .ring-content {
      position: relative;
      z-index: 1;
      text-align: center;
      color: #17396f;
    }

    .ring-content .num {
      font-size: 47px;
      font-weight: 800;
      line-height: 1;
    }

    .ring-content .txt {
      font-size: 13px;
      font-weight: 700;
      letter-spacing: .8px;
    }

    .legend {
      display: flex;
      gap: 18px;
      justify-content: center;
      flex-wrap: wrap;
    }

    .chip {
      border: 3px solid #23457e;
      border-radius: 12px;
      padding: 7px 12px;
      background: #fdf7ed;
      font-size: 16px;
      font-weight: 700;
      color: #17396f;
      display: inline-flex;
      align-items: center;
      gap: 10px;
    }

    .dot {
      width: 12px;
      height: 12px;
      border-radius: 50%;
      background: #1f4284;
      border: 2px solid #2b4064;
    }

    .dot.light {
      background: #d4dfef;
      border-color: #8f9cb2;
    }

    .welcome {
      background: #1f3f7a;
      color: #fff;
      position: relative;
      overflow: hidden;
      min-height: 200px;
    }

    .welcome h3 {
      margin: 0;
      font-size: 38px;
      line-height: 1.1;
      color: #f6c46c;
      font-weight: 800;
    }

    .welcome p {
      margin: 16px 0 0;
      max-width: 70%;
      font-size: 26px;
      line-height: 1.55;
      font-weight: 600;
      border-left: 5px solid #f6c46c;
      padding-left: 16px;
      color: #fff;
    }

    .blocks {
      position: absolute;
      right: 38px;
      top: 34px;
      display: grid;
      grid-template-columns: repeat(2, 58px);
      gap: 14px;
      opacity: .8;
    }

    .blocks span {
      width: 58px;
      height: 58px;
      border-radius: 14px;
      background: #2f5088;
      border: 6px solid #466499;
      display: block;
    }

    @media (max-width: 1300px) {

      h1,
      .page-title {
        font-size: 32px;
      }

      .section-title {
        font-size: 28px;
      }

      .subtitle {
        font-size: 16px;
      }

      .value {
        font-size: 34px;
      }

      .demographic-head {
        font-size: 16px;
      }

      .welcome h3 {
        font-size: 34px;
      }

      .welcome p {
        font-size: 22px;
      }
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

      .stats,
      .grid {
        grid-template-columns: 1fr;
      }

      .welcome p {
        max-width: 100%;
      }

      .blocks {
        display: none;
      }
    }

    /* QR modal styles */
    .qr-overlay {
      position: fixed;
      inset: 0;
      background: rgba(9, 18, 34, 0.55);
      display: none;
      align-items: center;
      justify-content: center;
      padding: 20px;
      z-index: 1300;
    }

    .qr-overlay.open {
      display: flex;
    }

    .qr-modal {
      width: 480px;
      max-width: calc(100% - 48px);
      max-height: calc(100vh - 80px);
      background: #fff;
      border-radius: 12px;
      overflow: hidden;
      display: flex;
      flex-direction: column;
      box-shadow: 0 12px 40px rgba(10, 20, 40, 0.35);
    }

    .qr-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 16px 18px;
      border-bottom: 1px solid #eef3f8;
    }

    .qr-header h3 {
      margin: 0;
      font-size: 20px;
      color: var(--blue-900);
      font-weight: 800;
      display: flex;
      gap: 10px;
      align-items: center;
    }

    .qr-close {
      width: 34px;
      height: 34px;
      border-radius: 50%;
      border: 2px solid #e6eef6;
      display: grid;
      place-items: center;
      background: #fff;
      cursor: pointer;
      font-weight: 800;
      color: #23457e;
    }

    .qr-body {
      padding: 18px 20px 22px;
      overflow-y: auto;
      flex: 1 1 auto;
      text-align: center;
      color: #5e738f;
    }

    .camera-box {
      width: 220px;
      height: 220px;
      margin: 18px auto 12px;
      border-radius: 12px;
      background: linear-gradient(180deg, #071226 0%, #0f233a 100%);
      border: 6px solid rgba(35, 69, 126, 0.12);
      box-shadow: 0 6px 14px rgba(17, 36, 64, 0.18) inset;
      position: relative;
      display: grid;
      place-items: center;
    }

    .camera-inner {
      width: 150px;
      height: 150px;
      border: 2px dashed rgba(255, 255, 255, 0.08);
      border-radius: 6px;
      box-sizing: border-box;
      position: relative;
    }

    .scan-line {
      position: absolute;
      left: 0;
      right: 0;
      top: 50%;
      height: 4px;
      background: linear-gradient(90deg, rgba(0, 200, 120, 0) 0%, rgba(0, 200, 120, 0.9) 50%, rgba(0, 200, 120, 0) 100%);
      transform: translateY(-50%);
      box-shadow: 0 0 18px rgba(0, 200, 120, 0.35);
      animation: scan 2s linear infinite;
    }

    @keyframes scan {
      0% {
        transform: translateY(-60%)
      }

      50% {
        transform: translateY(0)
      }

      100% {
        transform: translateY(60%)
      }
    }

    .qr-footer {
      padding: 14px 18px 18px;
      display: flex;
      gap: 12px;
      justify-content: center;
      align-items: center;
    }

    .pill {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      padding: 10px 18px;
      border-radius: 999px;
      background: #f4f6fa;
      color: #163a70;
      font-weight: 700;
    }

    .btn-sim {
      padding: 10px 18px;
      border-radius: 10px;
      border: 2px solid transparent;
      background: #fff;
      font-weight: 800;
      cursor: pointer;
    }

    .btn-valid {
      border-color: #bfeeda;
      background: #f6fffb;
      color: #0a8a3f;
    }

    .btn-invalid {
      border-color: #f7c8c8;
      background: #fff7f7;
      color: #d64545;
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
          <a href="{{ route('admin.dashboard') }}" class="active">
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
      <div class="topbar">
        <div>
          <h1 class="page-title">Dashboard Overview</h1>
          <p class="subtitle">Summary and statistics for all events.</p>
        </div>

        <button class="scan-btn" type="button">
          <svg viewBox="0 0 24 24">
            <path d="M4 8V5a1 1 0 0 1 1-1h3"></path>
            <path d="M20 8V5a1 1 0 0 0-1-1h-3"></path>
            <path d="M4 16v3a1 1 0 0 0 1 1h3"></path>
            <path d="M20 16v3a1 1 0 0 1-1 1h-3"></path>
            <circle cx="12" cy="12" r="2.5"></circle>
          </svg>
          OPEN QR SCANNER
        </button>
      </div>

      <section class="stats">
        <article class="card stat-card peach">
          <div>
            <div class="label">TOTAL PARTICIPANTS</div>
            <div class="value">{{ number_format($totalParticipants ?? 0) }}</div>
          </div>
          <div class="icon-box">
            <svg viewBox="0 0 24 24">
              <circle cx="8" cy="8" r="3"></circle>
              <circle cx="16" cy="9" r="2.5"></circle>
              <path d="M3 20c1-3 3.5-4.5 6-4.5S14 17 15 20"></path>
              <path d="M13 20c.6-2 2.3-3 4-3 1.6 0 3.1.9 4 3"></path>
            </svg>
          </div>
        </article>

        <article class="card stat-card gold">
          <div>
            <div class="label">PENDING PAPERS</div>
            <div class="value">{{ number_format($pendingPapers ?? 0) }}</div>
          </div>
          <div class="icon-box">
            <svg viewBox="0 0 24 24">
              <path d="M14 3H7a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8z"></path>
              <polyline points="14 3 14 8 19 8"></polyline>
              <line x1="9" y1="13" x2="15" y2="13"></line>
              <line x1="9" y1="17" x2="13" y2="17"></line>
            </svg>
          </div>
        </article>

        <article class="card stat-card blue">
          <div>
            <div class="label">ACTIVE EVENTS</div>
            <div class="value">{{ number_format($activeEvents ?? 0) }}</div>
          </div>
          <div class="icon-box">
            <svg viewBox="0 0 24 24">
              <rect x="3" y="5" width="18" height="16" rx="2"></rect>
              <line x1="8" y1="3" x2="8" y2="7"></line>
              <line x1="16" y1="3" x2="16" y2="7"></line>
            </svg>
          </div>
        </article>

        <article class="card stat-card white">
          <div>
            <div class="label">TOTAL CHECKED-IN</div>
            <div class="value">{{ number_format($totalCheckedIn ?? 0) }}</div>
          </div>
          <div class="icon-box">
            <svg viewBox="0 0 24 24">
              <circle cx="12" cy="12" r="9"></circle>
              <path d="M8 12l2.5 2.5L16 9"></path>
            </svg>
          </div>
        </article>
      </section>

      <section class="grid">
        <article class="card">
          <h2 class="section-title">PARTICIPANT DEMOGRAPHICS</h2>
          <div class="demographic">
            <div class="demographic-row">
              <div class="demographic-head"><span>Undergraduate</span><span>65%</span></div>
              <div class="track">
                <div class="fill" style="width: 65%"></div>
              </div>
            </div>
            <div class="demographic-row">
              <div class="demographic-head"><span>Senior High</span><span>20%</span></div>
              <div class="track">
                <div class="fill gold" style="width: 20%"></div>
              </div>
            </div>
            <div class="demographic-row">
              <div class="demographic-head"><span>Graduate</span><span>10%</span></div>
              <div class="track">
                <div class="fill gray" style="width: 10%"></div>
              </div>
            </div>
            <div class="demographic-row">
              <div class="demographic-head"><span>Professional/Faculty</span><span>5%</span></div>
              <div class="track">
                <div class="fill" style="width: 5%; background:#e8eef8"></div>
              </div>
            </div>
          </div>
        </article>

        <article class="card chart-card">
          <h2 class="section-title" style="text-align: center;">EVENT TYPES DISTRIBUTION</h2>
          <div class="chart-wrap">
            <div class="ring" aria-hidden="true">
              <div class="ring-content">
                <div class="num">12</div>
                <div class="txt">TOTAL</div>
              </div>
            </div>
            <div class="legend">
              <span class="chip"><span class="dot"></span> School (75%)</span>
              <span class="chip"><span class="dot light"></span> Conference (25%)</span>
            </div>
          </div>
        </article>
      </section>

      <section class="card welcome">
        <h3>WELCOME BACK, ADMIN!</h3>
        <p>
          Use the sidebar to navigate through events and participants. Make sure to review
          the pending research papers for the upcoming Tech Innovations Summit.
        </p>
        <div class="blocks" aria-hidden="true">
          <span></span><span></span><span></span><span></span>
        </div>
      </section>
    </main>
  </div>

  <!-- QR Scanner Modal -->
  <div id="qr-overlay" class="qr-overlay" aria-hidden="true">
    <div class="qr-modal" role="dialog" aria-modal="true" aria-labelledby="qr-title">
      <div class="qr-header">
        <h3 id="qr-title"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#23457e" stroke-width="2">
            <rect x="3" y="3" width="6" height="6" rx="1"></rect>
            <rect x="15" y="3" width="6" height="6" rx="1"></rect>
            <rect x="3" y="15" width="6" height="6" rx="1"></rect>
          </svg> QR Check-in</h3>
        <button type="button" class="qr-close" aria-label="Close">✕</button>
      </div>

      <div class="qr-body">
        <p style="margin:0 0 12px; color:#6f839e;">Position the participant's QR code within the frame to scan.</p>

        <div class="camera-box" aria-hidden="true">
          <div class="camera-inner">
            <div class="scan-line" aria-hidden="true"></div>
          </div>
        </div>

        <div style="margin-bottom:10px;">
          <span class="pill">Scanning...</span>
        </div>
      </div>

      <div class="qr-footer">
        <button type="button" class="btn-sim btn-valid">Simulate Valid</button>
        <button type="button" class="btn-sim btn-invalid">Simulate Invalid</button>
      </div>
    </div>
    <script>
      (function() {
        const openBtn = document.querySelector('.scan-btn');
        const overlay = document.getElementById('qr-overlay');
        const closeBtn = overlay ? overlay.querySelector('.qr-close') : null;
        const validBtn = overlay ? overlay.querySelector('.btn-valid') : null;
        const invalidBtn = overlay ? overlay.querySelector('.btn-invalid') : null;

        function openModal() {
          if (!overlay) return;
          overlay.classList.add('open');
          overlay.setAttribute('aria-hidden', 'false');
        }

        function closeModal() {
          if (!overlay) return;
          overlay.classList.remove('open');
          overlay.setAttribute('aria-hidden', 'true');
        }

        if (openBtn) openBtn.addEventListener('click', openModal);
        if (closeBtn) closeBtn.addEventListener('click', closeModal);

        // close when clicking outside the modal
        if (overlay) {
          overlay.addEventListener('click', function(e) {
            if (e.target === overlay) closeModal();
          });
        }

        // Escape to close
        document.addEventListener('keydown', function(e) {
          if (e.key === 'Escape') closeModal();
        });

        const validateQrUrl = "{{ route('admin.attendance.validate-qr') }}";
        const csrfToken = '{{ csrf_token() }}';

        async function submitQrValidation(qrCode) {
          const cleanQrCode = String(qrCode || '').trim();

          if (!cleanQrCode) {
            alert('Invalid');
            return;
          }

          try {
            const response = await fetch(validateQrUrl, {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json',
                'Accept': 'text/plain',
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
              },
              body: JSON.stringify({
                qr_code: cleanQrCode
              })
            });

            const statusText = (await response.text()).trim();
            const allowedStatuses = ['Valid', 'Already used', 'Invalid'];
            const status = allowedStatuses.includes(statusText) ? statusText : 'Invalid';

            alert(status);

            if (status === 'Valid' || status === 'Already used') {
              closeModal();
            }
          } catch (error) {
            alert('Invalid');
          }
        }

        if (validBtn) validBtn.addEventListener('click', function() {
          const scannedQr = window.prompt('Enter scanned QR code');
          if (scannedQr === null) return;
          submitQrValidation(scannedQr);
        });

        if (invalidBtn) invalidBtn.addEventListener('click', function() {
          submitQrValidation('__invalid_qr_value__');
        });
      })();
    </script>
  </div>
</body>

</html>