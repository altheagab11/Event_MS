{{-- QR SCANNER MODAL --}}
<div
    id="qrScannerModal"
    class="qr-modal-overlay hidden"
    role="dialog"
    aria-modal="true"
    aria-labelledby="qrScannerModalTitle"
    aria-hidden="true"
>
    <div class="qr-modal" onclick="event.stopPropagation()">
        <header class="qr-modal-header">
            <div class="flex items-center gap-3">
                <svg class="h-6 w-6 text-[#60A5FA]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 4h6v6H4V4zm10 0h6v6h-6V4zM4 14h6v6H4v-6zm10 0h6v6h-6v-6z"/>
                </svg>
                <h2 id="qrScannerModalTitle" class="text-lg font-black uppercase tracking-wide text-[#F8FAFC]">
                    QR Scanner
                </h2>
            </div>
            <button
                type="button"
                id="closeQrScannerButton"
                class="flex h-10 w-10 items-center justify-center rounded-full border border-[#60A5FA]/20 bg-[#13284A]/70 text-[#F8FAFC] transition hover:border-[#60A5FA]/50 hover:bg-[#13284A]"
                aria-label="Close QR scanner"
            >
                <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2.3" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </header>

        <div class="qr-modal-body">
            <div class="mb-5 flex gap-2 rounded-2xl border border-[#60A5FA]/15 bg-[#0D1B31]/60 p-1.5" role="tablist" aria-label="QR scanner modes">
                <button type="button" id="qrTabScan" class="qr-tab-btn active" role="tab" aria-selected="true" aria-controls="qrPanelScan">
                    Scan QR Code
                </button>
                <button type="button" id="qrTabManual" class="qr-tab-btn" role="tab" aria-selected="false" aria-controls="qrPanelManual">
                    Manual QR Entry
                </button>
            </div>

            <div id="qrPanelScan" class="qr-tab-panel space-y-4" role="tabpanel">
                <p class="text-sm text-[#CBD5E1]">
                    Point the camera at the QR code on the participant digital ID. Check-in is recorded automatically when a valid code is detected.
                </p>

                <div
                    id="qrScannerStatus"
                    class="hidden rounded-2xl border px-4 py-3 text-sm font-bold"
                    role="status"
                ></div>

                <div class="qr-camera-box">
                    <div id="qrReader"></div>
                    <div class="qr-scan-frame" aria-hidden="true"></div>
                </div>
            </div>

            <div id="qrPanelManual" class="qr-tab-panel hidden space-y-4" role="tabpanel">
                <p class="text-sm text-[#CBD5E1]">
                    Enter the exact QR code value printed on the participant digital ID. Only QR codes from the system can be used for check-in.
                </p>

                <div
                    id="qrManualStatus"
                    class="hidden rounded-2xl border px-4 py-3 text-sm font-bold"
                    role="status"
                ></div>

                <div>
                    <label for="qrManualInput" class="mb-2 block text-xs font-black uppercase tracking-widest text-[#94A3B8]">
                        Enter QR Code
                    </label>
                    <input
                        id="qrManualInput"
                        type="text"
                        inputmode="text"
                        autocomplete="off"
                        spellcheck="false"
                        placeholder="Type the QR code exactly as shown on the digital ID"
                        class="h-11 w-full rounded-2xl border border-[#60A5FA]/20 bg-[#0D1B31]/70 px-4 text-sm font-medium text-[#F8FAFC] outline-none transition placeholder:text-[#64748B] focus:border-[#60A5FA] focus:ring-2 focus:ring-[#60A5FA]/30"
                    >
                </div>

                <button
                    type="button"
                    id="qrManualSubmit"
                    class="w-full rounded-2xl bg-[#3B82F6] px-5 py-3.5 text-sm font-black uppercase tracking-wide text-white transition hover:bg-[#2563EB]"
                >
                    Verify QR Code
                </button>

                <div
                    id="qrManualSuccessCard"
                    class="hidden rounded-2xl border border-[#22C55E]/30 bg-[#22C55E]/10 p-5"
                >
                    <p class="text-xs font-black uppercase tracking-widest text-[#86EFAC]">Check-in successful</p>
                    <dl class="mt-4 space-y-3 text-sm">
                        <div class="flex justify-between gap-4 border-b border-[#22C55E]/15 pb-3">
                            <dt class="font-bold text-[#94A3B8]">Participant</dt>
                            <dd id="qrSuccessName" class="text-right font-black text-[#F8FAFC]">—</dd>
                        </div>
                        <div class="flex justify-between gap-4 border-b border-[#22C55E]/15 pb-3">
                            <dt class="font-bold text-[#94A3B8]">Email</dt>
                            <dd id="qrSuccessEmail" class="text-right font-medium text-[#CBD5E1]">—</dd>
                        </div>
                        <div class="flex justify-between gap-4 border-b border-[#22C55E]/15 pb-3">
                            <dt class="font-bold text-[#94A3B8]">Event</dt>
                            <dd id="qrSuccessEvent" class="text-right font-black text-[#F8FAFC]">—</dd>
                        </div>
                        <div class="flex justify-between gap-4 border-b border-[#22C55E]/15 pb-3">
                            <dt class="font-bold text-[#94A3B8]">Event Type</dt>
                            <dd id="qrSuccessEventType" class="text-right font-medium text-[#60A5FA]">—</dd>
                        </div>
                        <div class="flex justify-between gap-4 border-b border-[#22C55E]/15 pb-3">
                            <dt class="font-bold text-[#94A3B8]">Session</dt>
                            <dd id="qrSuccessSession" class="text-right font-medium text-[#CBD5E1]">—</dd>
                        </div>
                        <div class="flex justify-between gap-4 border-b border-[#22C55E]/15 pb-3">
                            <dt class="font-bold text-[#94A3B8]">Check-in Time</dt>
                            <dd id="qrSuccessCheckIn" class="text-right font-medium text-[#CBD5E1]">—</dd>
                        </div>
                        <div class="flex justify-between gap-4">
                            <dt class="font-bold text-[#94A3B8]">Attendance Status</dt>
                            <dd>
                                <span id="qrSuccessAttendance" class="inline-flex rounded-full border border-[#22C55E]/40 bg-[#22C55E]/15 px-3 py-1 text-xs font-black text-[#86EFAC]">—</span>
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
    (function () {
        const validateUrl = @json(route('admin.attendance.validate-qr'));
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        const modal = document.getElementById('qrScannerModal');
        const openButton = document.getElementById('openQrScannerButton');
        const closeButton = document.getElementById('closeQrScannerButton');
        const tabScan = document.getElementById('qrTabScan');
        const tabManual = document.getElementById('qrTabManual');
        const panelScan = document.getElementById('qrPanelScan');
        const panelManual = document.getElementById('qrPanelManual');
        const scanStatusBox = document.getElementById('qrScannerStatus');
        const manualStatusBox = document.getElementById('qrManualStatus');
        const manualSuccessCard = document.getElementById('qrManualSuccessCard');
        const manualInput = document.getElementById('qrManualInput');
        const manualSubmit = document.getElementById('qrManualSubmit');
        const cameraBox = document.querySelector('.qr-camera-box');
        const readerElementId = 'qrReader';

        let html5QrCode = null;
        let isProcessing = false;
        let lastScannedCode = '';
        let lastScannedAt = 0;
        let activeTab = 'scan';

        const applyStatusStyles = (element, type, message) => {
            if (!element) return;
            element.textContent = message;
            element.classList.remove('hidden', 'border-[#22C55E]/40', 'bg-[#22C55E]/12', 'text-[#86EFAC]', 'border-[#EF4444]/40', 'bg-[#EF4444]/12', 'text-[#FCA5A5]', 'border-[#FACC15]/40', 'bg-[#FACC15]/12', 'text-[#FACC15]');
            if (type === 'success') {
                element.classList.add('border-[#22C55E]/40', 'bg-[#22C55E]/12', 'text-[#86EFAC]');
            } else if (type === 'duplicate') {
                element.classList.add('border-[#FACC15]/40', 'bg-[#FACC15]/12', 'text-[#FACC15]');
            } else {
                element.classList.add('border-[#EF4444]/40', 'bg-[#EF4444]/12', 'text-[#FCA5A5]');
            }
        };

        const hideManualSuccessCard = () => {
            manualSuccessCard?.classList.add('hidden');
        };

        const showManualSuccessCard = (participant) => {
            if (!manualSuccessCard || !participant) return;
            document.getElementById('qrSuccessName').textContent = participant.name || '—';
            document.getElementById('qrSuccessEmail').textContent = participant.email || '—';
            document.getElementById('qrSuccessEvent').textContent = participant.event_name || '—';
            document.getElementById('qrSuccessEventType').textContent = participant.event_type || '—';
            document.getElementById('qrSuccessSession').textContent = participant.session_label || '—';
            document.getElementById('qrSuccessCheckIn').textContent = participant.check_in_time || '—';
            document.getElementById('qrSuccessAttendance').textContent = participant.attendance_status || 'Attended';
            manualSuccessCard.classList.remove('hidden');
        };

        const resetManualPanel = () => {
            hideManualSuccessCard();
            manualStatusBox?.classList.add('hidden');
            if (manualInput) {
                manualInput.value = '';
            }
        };

        const getQrBoxSize = () => {
            if (!cameraBox) {
                return { width: 200, height: 200 };
            }
            const frame = cameraBox.querySelector('.qr-scan-frame');
            const frameWidth = frame?.offsetWidth || 180;
            const frameHeight = frame?.offsetHeight || 180;
            return {
                width: Math.min(frameWidth, cameraBox.clientWidth - 24),
                height: Math.min(frameHeight, cameraBox.clientHeight - 24),
            };
        };

        const stopCameraTracks = () => {
            const video = document.querySelector('#qrReader video');
            const stream = video?.srcObject;
            if (stream && typeof stream.getTracks === 'function') {
                stream.getTracks().forEach((track) => track.stop());
            }
        };

        const switchTab = async (tab) => {
            activeTab = tab;
            const isScan = tab === 'scan';

            tabScan?.classList.toggle('active', isScan);
            tabManual?.classList.toggle('active', !isScan);
            tabScan?.setAttribute('aria-selected', isScan ? 'true' : 'false');
            tabManual?.setAttribute('aria-selected', !isScan ? 'true' : 'false');

            panelScan?.classList.toggle('hidden', !isScan);
            panelManual?.classList.toggle('hidden', isScan);

            if (isScan) {
                resetManualPanel();
                scanStatusBox?.classList.add('hidden');
                await startScanner();
            } else {
                await stopScanner();
            }
        };

        const postQrCode = async (qrCode, mode) => {
            const code = String(qrCode || '').trim();
            if (!code || isProcessing) {
                return null;
            }

            if (mode === 'scan') {
                const now = Date.now();
                if (code === lastScannedCode && now - lastScannedAt < 3000) {
                    return null;
                }
                lastScannedCode = code;
                lastScannedAt = now;
            }

            isProcessing = true;

            try {
                const response = await fetch(validateUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        Accept: 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    credentials: 'same-origin',
                    body: JSON.stringify({ qr_code: code }),
                });

                return await response.json().catch(() => ({}));
            } catch (error) {
                return { status: 'invalid', message: 'Network error. Please try again.' };
            } finally {
                setTimeout(() => {
                    isProcessing = false;
                }, mode === 'scan' ? 1200 : 400);
            }
        };

        const handleScanResult = (data) => {
            if (!data) return;

            const status = String(data.status || 'invalid');
            const message = String(data.message || 'Invalid QR Code.');

            if (status === 'success') {
                applyStatusStyles(scanStatusBox, 'success', message);
            } else if (status === 'duplicate') {
                applyStatusStyles(scanStatusBox, 'duplicate', message);
            } else {
                applyStatusStyles(scanStatusBox, 'error', message);
            }
        };

        const handleManualResult = (data) => {
            if (!data) return;

            hideManualSuccessCard();

            const status = String(data.status || 'invalid');
            const message = String(data.message || 'Invalid QR Code.');

            if (status === 'success') {
                applyStatusStyles(manualStatusBox, 'success', message);
                showManualSuccessCard(data.participant);
            } else if (status === 'duplicate') {
                applyStatusStyles(manualStatusBox, 'duplicate', message);
            } else {
                applyStatusStyles(manualStatusBox, 'error', message);
            }
        };

        const submitScanQrCode = async (qrCode) => {
            const data = await postQrCode(qrCode, 'scan');
            handleScanResult(data);
        };

        const submitManualQrCode = async () => {
            const data = await postQrCode(manualInput?.value, 'manual');
            handleManualResult(data);
        };

        const stopScanner = async () => {
            if (html5QrCode) {
                try {
                    if (html5QrCode.isScanning) {
                        await html5QrCode.stop();
                    }
                    html5QrCode.clear();
                } catch (error) {
                    // Scanner may already be stopped.
                }
                html5QrCode = null;
            }
            stopCameraTracks();
        };

        const startScanner = async () => {
            if (activeTab !== 'scan') return;

            if (typeof Html5Qrcode === 'undefined') {
                applyStatusStyles(scanStatusBox, 'error', 'QR scanner library failed to load.');
                return;
            }

            await stopScanner();
            html5QrCode = new Html5Qrcode(readerElementId, { verbose: false });

            const qrbox = getQrBoxSize();

            try {
                await html5QrCode.start(
                    { facingMode: 'environment' },
                    {
                        fps: 10,
                        qrbox,
                        aspectRatio: 1.333333,
                    },
                    (decodedText) => submitScanQrCode(decodedText),
                    () => {}
                );
            } catch (error) {
                applyStatusStyles(scanStatusBox, 'error', 'Unable to access camera. Use Manual QR Entry or allow camera permission.');
            }
        };

        const openModal = async () => {
            if (!modal) return;
            modal.classList.remove('hidden');
            modal.setAttribute('aria-hidden', 'false');
            document.body.classList.add('overflow-hidden');
            resetManualPanel();
            scanStatusBox?.classList.add('hidden');
            await switchTab('scan');
        };

        const closeModal = async () => {
            if (!modal) return;
            await stopScanner();
            modal.classList.add('hidden');
            modal.setAttribute('aria-hidden', 'true');
            document.body.classList.remove('overflow-hidden');
            resetManualPanel();
            activeTab = 'scan';
            tabScan?.classList.add('active');
            tabManual?.classList.remove('active');
            panelScan?.classList.remove('hidden');
            panelManual?.classList.add('hidden');
        };

        openButton?.addEventListener('click', openModal);
        closeButton?.addEventListener('click', closeModal);
        modal?.addEventListener('click', (event) => {
            if (event.target === modal) {
                closeModal();
            }
        });

        tabScan?.addEventListener('click', () => switchTab('scan'));
        tabManual?.addEventListener('click', () => switchTab('manual'));

        manualSubmit?.addEventListener('click', submitManualQrCode);
        manualInput?.addEventListener('keydown', (event) => {
            if (event.key === 'Enter') {
                event.preventDefault();
                submitManualQrCode();
            }
        });

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && modal && !modal.classList.contains('hidden')) {
                closeModal();
            }
        });
    })();
</script>
