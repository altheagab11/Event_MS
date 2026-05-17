<div class="event-card event-card--front">
    <div class="front-watermark" aria-hidden="true">QR</div>

    <div class="card-content front-layout">
        <div class="front-top">
            <div class="front-mark" aria-hidden="true">
                <span class="front-mark-icon"></span>
            </div>
            <div class="confirmed-badge">
                <span class="confirmed-badge-icon">&#10003;</span>
                CONFIRMED
            </div>
        </div>

        <div class="front-event">{{ $passData['event_name'] }}</div>

        <div class="front-name">{{ $passData['full_name'] }}</div>

        @if (! empty($passData['profile_line']))
            <div class="front-profile">{{ $passData['profile_line'] }}</div>
        @endif

        <div class="front-footer">
            <div class="front-footer-block">
                <div class="front-footer-label">VALID THRU</div>
                <div class="front-footer-value">{{ $passData['valid_thru'] ?? 'TBA' }}</div>
            </div>

            <div class="front-footer-block front-footer-block--email">
                <div class="front-footer-label">EMAIL</div>
                <div class="front-footer-value front-footer-email">{{ $passData['email'] }}</div>
            </div>

            <div class="front-scan-hint" aria-hidden="true">
                <span class="front-scan-icon"></span>
                <span>Tap to scan</span>
            </div>
        </div>
    </div>
</div>
