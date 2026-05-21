<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>EMS Digital Event Pass</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @include('digital-pass.partials.styles')
    <style>
        body {
            margin: 0;
            min-height: 100vh;
            background: #030914;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
            color: #ffffff;
        }

        .page-header {
            text-align: center;
            max-width: 760px;
            margin-bottom: 28px;
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

        .actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            justify-content: center;
            margin-top: 24px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 12px 22px;
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

        .flip-hint {
            margin-top: 14px;
            text-align: center;
            color: #7eb8ff;
            font-size: 13px;
            letter-spacing: 0.3px;
        }

        .flip-scene {
            width: 100%;
            max-width: 760px;
            perspective: 1400px;
        }

        .flip-card {
            position: relative;
            width: 100%;
            max-width: 760px;
            height: 410px;
            min-height: 410px;
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
            margin-top: 14px;
            text-align: center;
            font-size: 14px;
            letter-spacing: 4px;
            color: #b9c6d8;
            font-weight: 700;
        }

        @media (max-width: 700px) {
            .flip-card {
                height: auto;
                min-height: 410px;
            }
        }

        @media print {
            body {
                background: #ffffff;
                padding: 0;
            }

            .page-header,
            .actions,
            .flip-hint,
            .side-label {
                display: none;
            }

            .flip-scene,
            .flip-card,
            .flip-face {
                position: static;
                transform: none !important;
                min-height: auto;
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
    <div class="page-header">
        <h1>Hi {{ $passData['full_name'] }},</h1>
        @if (! empty($passData['uses_venue_qr_scan']))
            <p>Your digital event pass is ready. Present the QR code on the back at the venue for staff to scan and record your attendance.</p>
        @else
            <p>Your digital event pass is ready. Use the QR code or the online check-in button to confirm attendance during the online session.</p>
        @endif
    </div>

    <div class="flip-scene" id="passFlipScene" role="button" tabindex="0" aria-label="Flip event pass card">
        <div class="flip-card" id="passFlipCard">
            <div class="flip-face front">
                @include('digital-pass.partials.front')
            </div>
            <div class="flip-face back">
                @include('digital-pass.partials.back')
            </div>
        </div>
        <div class="side-label" id="passSideLabel">FRONT</div>
    </div>

    <div class="flip-hint">Click or tap the card to flip</div>

    <div class="actions">
        <button type="button" class="btn btn-secondary" id="flipPassBtn">Flip Card</button>
        @if (! empty($passData['online_attendance_url']) && empty($passData['uses_venue_qr_scan']))
            <a href="{{ $passData['online_attendance_url'] }}" class="btn btn-primary">Check In Online</a>
        @endif
        <a href="{{ $passData['download_url'] }}" class="btn btn-primary">Download Pass</a>
        <button type="button" class="btn btn-secondary" onclick="window.print()">Print</button>
    </div>

    <script>
        (function () {
            const scene = document.getElementById('passFlipScene');
            const label = document.getElementById('passSideLabel');
            const flipBtn = document.getElementById('flipPassBtn');

            function toggleFlip() {
                const flipped = scene.classList.toggle('is-flipped');
                label.textContent = flipped ? 'BACK' : 'FRONT';
            }

            scene.addEventListener('click', function (event) {
                if (event.target.closest('.actions')) {
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
        })();
    </script>
</body>

</html>
