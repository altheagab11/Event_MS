@php
  $displayCode = preg_replace('/^EMS-/i', '', (string) ($passData['pass_code'] ?? ''));
  $displayCode = $displayCode !== '' ? strtoupper($displayCode) : (string) ($passData['pass_code'] ?? '');
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
                <span class="back-code-text">{{ $displayCode }}</span>
            </div>
        </div>

        <div class="back-footer">
            <div class="back-brand-mark" aria-hidden="true">
                <span class="back-brand-box">EMS</span>
                <span class="back-brand-divider">|</span>
                <span class="back-brand-text">EVENTS</span>
            </div>
            <div class="back-scan-btn" aria-hidden="true">
                <span class="back-scan-btn-icon"></span>
            </div>
        </div>
    </div>
</div>
