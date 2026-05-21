@php
  $qrCode = strtoupper(trim((string) ($passData['qr_code'] ?? $passData['pass_code'] ?? '')));
@endphp
<div class="event-card event-card--back back-card">
    <div class="card-content back-layout">
        <div class="back-main">
            <div class="back-top-logo" aria-hidden="true">
                <span class="back-top-logo-mark">E</span><span class="back-top-logo-mark back-top-logo-mark--alt">M</span><span class="back-top-logo-mark">S</span>
            </div>

            <div class="back-qr-shell">
                {!! $passData['qr_svg'] ?? '' !!}
            </div>

            <div class="back-code-pill">
                <span class="back-code-icon" aria-hidden="true"></span>
                <span class="back-code-text">{{ $qrCode }}</span>
            </div>

            @if (! empty($passData['attendance_mode_label']))
                <div class="back-attendance-meta">
                    <div><span class="back-attendance-label">Attendance Mode:</span> {{ $passData['attendance_mode_label'] }}</div>
                    <div><span class="back-attendance-label">Check-in Method:</span> {{ $passData['checkin_method_label'] ?? '—' }}</div>
                </div>
            @endif
        </div>

        <div class="back-footer">
            <div class="back-brand-mark" aria-hidden="true">
                <span class="back-brand-box">EMS</span>
                <span class="back-brand-divider">|</span>
                <span class="back-brand-text">EVENTS</span>
            </div>
            @if (! empty($passData['online_attendance_url']) && empty($passData['uses_venue_qr_scan']))
                <a href="{{ $passData['online_attendance_url'] }}" class="back-checkin-link" target="_blank" rel="noopener noreferrer">
                    Online Check-In
                </a>
            @else
                <div class="back-scan-btn" aria-hidden="true" title="Venue QR scan">
                    <span class="back-scan-btn-icon"></span>
                </div>
            @endif
        </div>
    </div>
</div>
