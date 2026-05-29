<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>EMS Digital Event Pass</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @include('digital-pass.partials.styles')
    <style>
        html {
            height: 100%;
        }

        body {
            margin: 0;
            min-height: 100%;
            background: #030914;
            color: #ffffff;
            overflow-x: hidden;
            overflow-y: auto;
            padding: 24px 20px 120px;
        }

        .page-shell {
            width: 100%;
            max-width: 760px;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .page-header {
            text-align: center;
            width: 100%;
            margin-bottom: 20px;
            flex-shrink: 0;
        }

        .page-header h1 {
            margin: 0 0 8px;
            font-size: 22px;
            font-weight: 700;
        }

        .page-header p {
            margin: 0;
            color: #b9c6d8;
            font-size: 14px;
            line-height: 1.5;
        }

        .flip-viewport {
            width: 100%;
            max-width: 760px;
            overflow: hidden;
        }

        .flip-scale {
            width: 100%;
            transform-origin: top center;
        }

        .flip-scene {
            width: 100%;
            perspective: 1400px;
        }

        .flip-card {
            position: relative;
            width: 100%;
            transform-style: preserve-3d;
            transition: transform 0.7s cubic-bezier(0.4, 0.2, 0.2, 1);
            cursor: pointer;
        }

        .flip-scene.is-flipped .flip-card {
            transform: rotateY(180deg);
        }

        .flip-face {
            position: absolute;
            inset: 0;
            width: 100%;
            backface-visibility: hidden;
            -webkit-backface-visibility: hidden;
        }

        .flip-face.back {
            transform: rotateY(180deg);
        }

        .side-label {
            margin-top: 12px;
            text-align: center;
            font-size: 14px;
            letter-spacing: 4px;
            color: #b9c6d8;
            font-weight: 700;
        }

        .flip-hint {
            margin-top: 12px;
            text-align: center;
            color: #7eb8ff;
            font-size: 13px;
            letter-spacing: 0.3px;
        }

        .page-actions {
            position: fixed;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 100;
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            justify-content: center;
            padding: 14px 20px calc(14px + env(safe-area-inset-bottom, 0px));
            background: rgba(3, 9, 20, 0.96);
            border-top: 1px solid rgba(100, 160, 255, 0.22);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 12px 20px;
            border-radius: 999px;
            font-size: 14px;
            font-weight: 700;
            letter-spacing: 0.4px;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: transform 0.15s ease, box-shadow 0.15s ease;
        }

        .btn-primary {
            background: linear-gradient(135deg, #27c6ff, #0052c9);
            color: #ffffff;
            box-shadow: 0 10px 24px rgba(0, 82, 201, 0.35);
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.08);
            color: #dce9ff;
            border: 1px solid rgba(100, 160, 255, 0.45);
        }

        .btn:hover {
            transform: translateY(-1px);
        }

        @media (max-width: 700px) {
            body {
                padding-top: 16px;
                padding-bottom: 130px;
            }

            .page-header h1 {
                font-size: 20px;
            }

            .page-header p {
                font-size: 13px;
            }

            .btn {
                padding: 11px 16px;
                font-size: 13px;
            }
        }

        @media print {
            body {
                background: #ffffff;
                padding: 0;
            }

            .page-header,
            .page-actions,
            .flip-hint,
            .side-label {
                display: none;
            }

            .flip-viewport,
            .flip-scale,
            .flip-scene,
            .flip-card,
            .flip-face {
                position: static;
                transform: none !important;
                height: auto !important;
                min-height: auto;
                overflow: visible;
            }

            .flip-face.back {
                page-break-before: always;
                margin-top: 24px;
            }

            .event-card {
                box-shadow: none;
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
            }
        }
    </style>
</head>

<body>
    <div class="page-shell">
        <div class="page-header">
            <h1>Hi {{ $passData['full_name'] }},</h1>
            @if (! empty($passData['uses_venue_qr_scan']))
                <p>Your digital event pass is ready. Present the QR code on the back at the venue for staff to scan and record your attendance.</p>
            @else
                <p>Your digital event pass is ready. Use the QR code or the online check-in button to confirm attendance during the online session.</p>
            @endif
        </div>

        <div class="flip-viewport" id="passFlipViewport">
            <div class="flip-scale" id="passFlipScale">
                <div class="flip-scene" id="passFlipScene" role="button" tabindex="0" aria-label="Flip event pass card">
                    <div class="flip-card" id="passFlipCard">
                        <div class="flip-face front">
                            @include('digital-pass.partials.front')
                        </div>
                        <div class="flip-face back">
                            @include('digital-pass.partials.back')
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="side-label" id="passSideLabel">FRONT</div>
        <div class="flip-hint">Click or tap the card to flip</div>
    </div>

    <div class="page-actions">
        <button type="button" class="btn btn-secondary" id="flipPassBtn">Flip Card</button>
        @if (! empty($passData['online_attendance_url']) && empty($passData['uses_venue_qr_scan']))
            <a href="{{ $passData['online_attendance_url'] }}" class="btn btn-primary">Check In Online</a>
        @endif
        <a href="{{ $passData['download_url'] }}" class="btn btn-primary">Download Pass</a>
        <button type="button" class="btn btn-secondary" onclick="window.print()">Print</button>
    </div>

    <script>
        (function () {
            const viewport = document.getElementById('passFlipViewport');
            const scaleWrap = document.getElementById('passFlipScale');
            const scene = document.getElementById('passFlipScene');
            const card = document.getElementById('passFlipCard');
            const label = document.getElementById('passSideLabel');
            const flipBtn = document.getElementById('flipPassBtn');
            const actions = document.querySelector('.page-actions');

            function measureCardHeight() {
                const faces = card.querySelectorAll('.flip-face');
                let maxHeight = 0;

                faces.forEach(function (face) {
                    const previousPosition = face.style.position;
                    const previousTransform = face.style.transform;
                    const previousVisibility = face.style.backfaceVisibility;

                    face.style.position = 'static';
                    face.style.transform = 'none';
                    face.style.backfaceVisibility = 'visible';

                    maxHeight = Math.max(maxHeight, face.offsetHeight);

                    face.style.position = previousPosition;
                    face.style.transform = previousTransform;
                    face.style.backfaceVisibility = previousVisibility;
                });

                return maxHeight;
            }

            function fitPassCard() {
                if (!viewport || !scaleWrap || !card) {
                    return;
                }

                scaleWrap.style.transform = 'none';
                card.style.height = 'auto';
                card.style.minHeight = '0';

                const contentHeight = measureCardHeight();
                const headerBottom = document.querySelector('.page-header')?.getBoundingClientRect().bottom ?? 0;
                const actionsHeight = actions?.offsetHeight ?? 96;
                const reservedSpace = headerBottom + actionsHeight + 110;
                const availableHeight = Math.max(260, window.innerHeight - reservedSpace);
                const scale = contentHeight > 0 ? Math.min(1, availableHeight / contentHeight) : 1;

                card.style.height = contentHeight + 'px';
                card.style.minHeight = contentHeight + 'px';

                if (scale < 1) {
                    scaleWrap.style.transform = 'scale(' + scale + ')';
                    viewport.style.height = Math.round(contentHeight * scale) + 'px';
                } else {
                    scaleWrap.style.transform = 'none';
                    viewport.style.height = contentHeight + 'px';
                }
            }

            function toggleFlip() {
                const flipped = scene.classList.toggle('is-flipped');
                label.textContent = flipped ? 'BACK' : 'FRONT';
            }

            scene.addEventListener('click', function (event) {
                if (event.target.closest('.page-actions')) {
                    return;
                }
                toggleFlip();
            });

            scene.addEventListener('keydown', function (event) {
                if (event.key === 'Enter' || event.key === ' ') {
                    event.preventDefault();
                    toggleFlip();
                }
            });

            flipBtn.addEventListener('click', function (event) {
                event.stopPropagation();
                toggleFlip();
            });

            fitPassCard();
            window.addEventListener('resize', fitPassCard);
            window.addEventListener('load', fitPassCard);
        })();
    </script>
</body>

</html>