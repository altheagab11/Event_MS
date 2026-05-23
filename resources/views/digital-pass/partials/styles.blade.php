<style>
    * {
        box-sizing: border-box;
        font-family: 'Inter', 'Segoe UI', Arial, sans-serif;
    }

    .event-card {
        width: 100%;
        max-width: 760px;
        min-height: 410px;
        border-radius: 22px;
        position: relative;
        overflow: hidden;
        padding: 30px 34px;
        background:
            radial-gradient(circle at top right, rgba(0, 125, 255, 0.45), transparent 35%),
            linear-gradient(135deg, #06142d 0%, #08295f 50%, #0052c9 100%);
        border: 1px solid rgba(100, 160, 255, 0.55);
        box-shadow: 0 24px 60px rgba(0, 0, 0, 0.45);
    }

    .event-card::before {
        content: "";
        position: absolute;
        inset: 0;
        background:
            linear-gradient(120deg, transparent 45%, rgba(255, 255, 255, 0.07) 46%, transparent 60%),
            radial-gradient(circle at 75% 35%, rgba(255, 255, 255, 0.08), transparent 20%);
        pointer-events: none;
    }

    .event-card--front,
    .event-card--back {
        width: 100%;
        max-width: 760px;
        min-height: 410px;
        height: 410px;
        padding: 30px 34px;
        background:
            radial-gradient(circle at 72% 42%, rgba(72, 140, 255, 0.22), transparent 34%),
            linear-gradient(135deg, #0b1f3f 0%, #123768 48%, #1d4f9c 100%);
        border: 1px solid rgba(100, 160, 255, 0.55);
        box-shadow: 0 24px 60px rgba(0, 0, 0, 0.45);
    }

    .event-card--front::before,
    .event-card--back::before {
        background:
            linear-gradient(120deg, transparent 40%, rgba(255, 255, 255, 0.05) 48%, transparent 58%),
            radial-gradient(circle at 78% 30%, rgba(255, 255, 255, 0.06), transparent 24%);
    }

    .event-card--front::after,
    .event-card--back::after {
        display: none;
    }

    .front-watermark {
        position: absolute;
        right: 34px;
        top: 50%;
        transform: translateY(-52%);
        font-size: clamp(88px, 16vw, 132px);
        font-weight: 900;
        letter-spacing: -6px;
        color: rgba(255, 255, 255, 0.06);
        pointer-events: none;
        user-select: none;
        z-index: 1;
    }

    .front-layout,
    .back-layout {
        display: flex;
        flex-direction: column;
        min-height: 350px;
        height: 100%;
    }

    .front-top {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 12px;
    }

    .front-mark-icon {
        display: block;
        width: 34px;
        height: 34px;
        border-radius: 8px;
        background:
            linear-gradient(135deg, #ff6b8a 25%, transparent 25%) 0 0 / 14px 14px,
            linear-gradient(225deg, #ff6b8a 25%, transparent 25%) 7px 7px / 14px 14px,
            linear-gradient(45deg, #ff8fab 25%, transparent 25%) 0 7px / 14px 14px,
            linear-gradient(315deg, #ff8fab 25%, #ff4d7d 25%) 7px 0 / 14px 14px;
        box-shadow: 0 0 0 2px rgba(255, 120, 150, 0.35);
    }

    .front-mark-text {
        display: inline-block;
        font-size: 28px;
        line-height: 1;
        font-weight: 800;
        letter-spacing: 1.4px;
        color: rgba(255, 255, 255, 0.18);
        font-family: 'Segoe UI', Arial, Helvetica, sans-serif;
    }

    .confirmed-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 7px 14px;
        border-radius: 999px;
        background: rgba(34, 197, 94, 0.18);
        border: 1px solid rgba(74, 222, 128, 0.45);
        color: #86efac;
        font-size: 12px;
        font-weight: 800;
        letter-spacing: 1.2px;
    }

    .confirmed-badge-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 18px;
        height: 18px;
        border-radius: 50%;
        background: rgba(34, 197, 94, 0.25);
        font-size: 11px;
    }

    .front-event {
        margin-top: 18px;
        font-size: clamp(18px, 2.6vw, 24px);
        font-weight: 700;
        color: rgba(255, 255, 255, 0.92);
        letter-spacing: 0.2px;
    }

    .front-schedule {
        margin-top: 8px;
        font-size: clamp(12px, 1.8vw, 15px);
        font-weight: 600;
        line-height: 1.35;
        color: rgba(255, 255, 255, 0.82);
        letter-spacing: 0.1px;
        word-break: break-word;
    }

    .front-name {
        margin-top: 10px;
        font-size: clamp(34px, 6vw, 52px);
        font-weight: 800;
        line-height: 1.05;
        color: #ffffff;
        letter-spacing: 0.2px;
        word-break: break-word;
    }

    .front-profile {
        margin-top: 10px;
        font-size: clamp(14px, 2.2vw, 18px);
        font-weight: 500;
        color: rgba(255, 255, 255, 0.88);
        line-height: 1.45;
        word-break: break-word;
    }

    .front-attendance {
        margin-top: 14px;
        padding: 12px 14px;
        border-radius: 14px;
        background: rgba(255, 255, 255, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.14);
    }

    .front-attendance-row {
        display: flex;
        flex-wrap: wrap;
        gap: 6px 10px;
        font-size: 13px;
        line-height: 1.45;
        color: rgba(255, 255, 255, 0.92);
    }

    .front-attendance-row + .front-attendance-row {
        margin-top: 6px;
    }

    .front-attendance-label {
        font-weight: 700;
        color: rgba(255, 255, 255, 0.72);
    }

    .front-attendance-value {
        font-weight: 600;
    }

    .front-footer {
        margin-top: auto;
        padding-top: 28px;
        display: grid;
        grid-template-columns: minmax(90px, 120px) minmax(0, 1fr) auto;
        gap: 18px 24px;
        align-items: end;
    }

    .front-footer-label {
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 1.4px;
        color: rgba(255, 255, 255, 0.72);
        margin-bottom: 6px;
    }

    .front-footer-value {
        font-size: clamp(18px, 3vw, 24px);
        font-weight: 600;
        color: #ffffff;
        line-height: 1.2;
    }

    .front-footer-email {
        font-size: clamp(14px, 2.2vw, 17px);
        font-weight: 500;
        text-decoration: underline;
        text-underline-offset: 3px;
        word-break: break-all;
    }

    .front-scan-hint {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
        color: rgba(255, 255, 255, 0.55);
        font-size: 12px;
        text-align: center;
        min-width: 72px;
    }

    .front-mark-text {
        display: inline-block;
        font-size: 14px;
        line-height: 1;
        font-weight: 800;
        letter-spacing: 1.4px;
        color: #ffffff;
        font-family: 'Segoe UI', Arial, Helvetica, sans-serif;
        height: 100%;
    }

    .top-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
    }

    .brand {
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .logo {
        width: 74px;
        height: 62px;
        border: 3px solid #6fa8ff;
        clip-path: polygon(25% 0, 75% 0, 100% 50%, 75% 100%, 25% 100%, 0 50%);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 900;
        font-size: 24px;
        letter-spacing: 1px;
        background: rgba(255, 255, 255, 0.08);
        flex-shrink: 0;
    }

    .brand-title {
        font-size: 20px;
        font-weight: 800;
        letter-spacing: 1px;
        color: #ffffff;
    }

    .brand-subtitle {
        font-size: 14px;
        color: #36c8ff;
        margin-top: 4px;
    }

    .verified {
        color: #39f2d2;
        font-size: 14px;
        font-weight: 800;
        letter-spacing: 1.5px;
        display: flex;
        align-items: center;
        gap: 8px;
        flex-shrink: 0;
    }

    .verified-icon {
        width: 28px;
        height: 28px;
        border: 2px solid #39f2d2;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .main-title {
        margin-top: 30px;
    }

    .main-title .small {
        font-size: 28px;
        font-weight: 600;
        color: #f5f8ff;
    }

    .main-title .event-name {
        font-size: clamp(36px, 8vw, 74px);
        font-weight: 900;
        line-height: 1;
        margin-top: 8px;
        letter-spacing: 1px;
        color: #ffffff;
        word-break: break-word;
    }

    .divider {
        height: 1px;
        background: rgba(57, 220, 255, 0.5);
        margin: 18px 0 22px;
    }

    .details-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px 34px;
    }

    .detail-item {
        display: flex;
        align-items: flex-start;
        gap: 14px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        padding-bottom: 12px;
    }

    .icon {
        color: #27c6ff;
        font-size: 24px;
        width: 28px;
        text-align: center;
        flex-shrink: 0;
    }

    .detail-label {
        color: #35d5ff;
        font-size: 13px;
        font-weight: 700;
        margin-bottom: 6px;
    }

    .detail-value {
        color: #ffffff;
        font-size: 18px;
        font-weight: 500;
        word-break: break-word;
    }

    .detail-value.code {
        font-weight: 900;
        letter-spacing: 2px;
    }

    .back-layout {
        position: relative;
    }

    .back-main {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 16px;
        padding-bottom: 48px;
    }

    .back-top-logo {
        display: flex;
        align-items: center;
        gap: 2px;
        margin-bottom: 4px;
    }

    .back-top-logo-mark {
        font-size: 26px;
        font-weight: 900;
        color: rgba(255, 255, 255, 0.95);
        line-height: 1;
        letter-spacing: -1px;
        text-shadow: 0 2px 12px rgba(255, 255, 255, 0.35);
    }

    .back-top-logo-mark--alt {
        opacity: 0.82;
        transform: translateY(-1px);
    }

    .back-qr-shell {
        background: #ffffff;
        border-radius: 22px;
        padding: 14px;
        box-shadow:
            0 16px 36px rgba(52, 96, 150, 0.22),
            0 0 0 1px rgba(255, 255, 255, 0.8);
    }

    .back-qr-shell svg {
        width: 190px;
        height: 190px;
        max-width: 100%;
        display: block;
    }

    .back-code-pill {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 10px 20px;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.38);
        border: 1px solid rgba(255, 255, 255, 0.55);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        box-shadow: 0 8px 20px rgba(70, 120, 180, 0.12);
    }

    .back-code-icon {
        width: 18px;
        height: 18px;
        border-radius: 50%;
        border: 2px solid rgba(255, 255, 255, 0.9);
        position: relative;
        flex-shrink: 0;
    }

    .back-code-icon::before {
        content: "";
        position: absolute;
        inset: 4px;
        border-radius: 50%;
        border: 2px solid rgba(255, 255, 255, 0.85);
        border-top-color: transparent;
        border-right-color: transparent;
        transform: rotate(-35deg);
    }

    .back-code-text {
        font-size: 15px;
        font-weight: 800;
        letter-spacing: 2px;
        color: rgba(255, 255, 255, 0.98);
        text-shadow: 0 1px 8px rgba(90, 140, 200, 0.25);
    }

    .back-attendance-meta {
        margin-top: 14px;
        text-align: center;
        font-size: 12px;
        line-height: 1.5;
        color: rgba(255, 255, 255, 0.88);
    }

    .back-attendance-meta div + div {
        margin-top: 4px;
    }

    .back-attendance-label {
        font-weight: 700;
        color: rgba(255, 255, 255, 0.68);
    }

    .back-checkin-link {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 10px 16px;
        border-radius: 999px;
        background: linear-gradient(135deg, #27c6ff, #0052c9);
        color: #ffffff;
        font-size: 12px;
        font-weight: 800;
        letter-spacing: 0.6px;
        text-decoration: none;
        text-transform: uppercase;
        box-shadow: 0 8px 20px rgba(0, 82, 201, 0.28);
        flex-shrink: 0;
    }

    .back-checkin-link:hover {
        color: #ffffff;
        filter: brightness(1.05);
    }

    .flip-face .event-card--front,
    .flip-face .event-card--back {
        height: 100%;
        min-height: 100%;
    }

    .back-footer {
        position: absolute;
        left: 0;
        right: 0;
        bottom: 0;
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 16px;
    }

    .back-brand-mark {
        display: flex;
        align-items: center;
        gap: 10px;
        color: rgba(255, 255, 255, 0.42);
        font-weight: 800;
        letter-spacing: 1px;
    }

    .back-brand-box {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 52px;
        padding: 4px 8px;
        border: 2px solid rgba(255, 255, 255, 0.38);
        font-size: 18px;
        line-height: 1;
    }

    .back-brand-divider {
        font-size: 20px;
        font-weight: 300;
        opacity: 0.7;
    }

    .back-brand-text {
        font-size: 22px;
        letter-spacing: 3px;
        opacity: 0.85;
    }

    .back-scan-btn {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        border: 2px solid rgba(255, 255, 255, 0.38);
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(255, 255, 255, 0.12);
        flex-shrink: 0;
    }

    .back-scan-btn-icon {
        display: block;
        width: 18px;
        height: 18px;
        border: 2px solid rgba(255, 255, 255, 0.72);
        border-radius: 4px;
        position: relative;
    }

    .back-scan-btn-icon::before,
    .back-scan-btn-icon::after {
        content: "";
        position: absolute;
        width: 6px;
        height: 6px;
        border: 2px solid rgba(255, 255, 255, 0.72);
    }

    .back-scan-btn-icon::before {
        top: -5px;
        left: -5px;
        border-right: none;
        border-bottom: none;
        border-radius: 2px 0 0 0;
    }

    .back-scan-btn-icon::after {
        right: -5px;
        bottom: -5px;
        border-left: none;
        border-top: none;
        border-radius: 0 0 2px 0;
    }

    @media (max-width: 700px) {
        .details-grid {
            grid-template-columns: 1fr;
        }

        .brand-title {
            font-size: 16px;
        }

        .event-card--front,
        .event-card--back {
            height: auto;
            min-height: 410px;
        }

        .back-qr-shell svg {
            width: clamp(150px, 42vw, 190px);
            height: clamp(150px, 42vw, 190px);
        }

        .front-footer {
            grid-template-columns: 1fr 1fr;
        }

        .front-scan-hint {
            grid-column: 1 / -1;
            flex-direction: row;
            justify-content: flex-end;
        }
    }
</style>
