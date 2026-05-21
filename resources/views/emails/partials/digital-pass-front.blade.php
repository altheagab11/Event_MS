@php
  $profileLine = trim((string) ($passData['profile_line'] ?? ''));
  if ($profileLine === '') {
      $profileLine = 'Event Participant';
  }
@endphp
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:760px; border-collapse:separate; border-spacing:0; border-radius:22px; border:1px solid rgba(100,160,255,0.55); background-color:#123768; background-image:linear-gradient(135deg,#0b1f3f 0%,#123768 48%,#1d4f9c 100%); box-shadow:0 24px 60px rgba(0,0,0,0.22);">
  <tr>
    <td style="padding:30px 34px;">
      <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
        <tr>
          <td width="50%" style="vertical-align:top;">
            <span style="display:inline-block; width:34px; height:34px; border-radius:8px; border:2px solid rgba(255,120,150,0.35); background-color:#ff6b8a;"></span>
          </td>
          <td width="50%" style="vertical-align:top; text-align:right;">
            <span style="display:inline-block; padding:7px 14px; border-radius:999px; background:rgba(34,197,94,0.18); border:1px solid rgba(74,222,128,0.45); color:#86efac; font-size:12px; font-weight:800; letter-spacing:1.2px; font-family:'Segoe UI',Arial,Helvetica,sans-serif;">
              <span style="display:inline-block; width:18px; height:18px; line-height:18px; border-radius:50%; background:rgba(34,197,94,0.25); text-align:center; font-size:11px; margin-right:6px; vertical-align:middle;">&#10003;</span>
              <span style="vertical-align:middle;">CONFIRMED</span>
            </span>
          </td>
        </tr>
        <tr>
          <td colspan="2" style="padding-top:18px; font-size:24px; line-height:1.25; font-weight:700; color:rgba(255,255,255,0.92); font-family:'Segoe UI',Arial,Helvetica,sans-serif;">
            {{ $passData['event_name'] }}
          </td>
        </tr>
        <tr>
          <td colspan="2" style="padding-top:10px; font-size:48px; line-height:1.05; font-weight:800; color:#ffffff; font-family:'Segoe UI',Arial,Helvetica,sans-serif; word-break:break-word;">
            {{ $passData['full_name'] }}
          </td>
        </tr>
        <tr>
          <td colspan="2" style="padding-top:10px; font-size:18px; line-height:1.45; font-weight:500; color:rgba(255,255,255,0.88); font-family:'Segoe UI',Arial,Helvetica,sans-serif; word-break:break-word;">
            {{ $profileLine }}
          </td>
        </tr>
        @if (! empty($passData['attendance_mode_label']))
        <tr>
          <td colspan="2" style="padding-top:14px;">
            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border-radius:14px; background:rgba(255,255,255,0.08); border:1px solid rgba(255,255,255,0.14);">
              <tr>
                <td style="padding:12px 14px; font-size:13px; line-height:1.5; color:rgba(255,255,255,0.92); font-family:'Segoe UI',Arial,Helvetica,sans-serif;">
                  <strong style="color:rgba(255,255,255,0.72);">Attendance Mode:</strong> {{ $passData['attendance_mode_label'] }}<br>
                  <strong style="color:rgba(255,255,255,0.72);">Check-in Method:</strong> {{ $passData['checkin_method_label'] ?? '—' }}
                </td>
              </tr>
            </table>
          </td>
        </tr>
        @endif
        <tr>
          <td colspan="2" style="padding-top:28px;">
            <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td width="28%" style="vertical-align:bottom; padding-right:12px;">
                  <span style="font-size:11px; font-weight:700; letter-spacing:1.4px; color:rgba(255,255,255,0.72); font-family:'Segoe UI',Arial,Helvetica,sans-serif;">VALID THRU</span>
                  <p style="margin:6px 0 0; font-size:24px; line-height:1.2; font-weight:600; color:#ffffff; font-family:'Segoe UI',Arial,Helvetica,sans-serif;">{{ $passData['valid_thru'] ?? 'TBA' }}</p>
                </td>
                <td width="52%" style="vertical-align:bottom; padding-right:12px;">
                  <span style="font-size:11px; font-weight:700; letter-spacing:1.4px; color:rgba(255,255,255,0.72); font-family:'Segoe UI',Arial,Helvetica,sans-serif;">EMAIL</span>
                  <p style="margin:6px 0 0; font-size:17px; line-height:1.3; font-weight:500; color:#ffffff; text-decoration:underline; word-break:break-all; font-family:'Segoe UI',Arial,Helvetica,sans-serif;">{{ $passData['email'] }}</p>
                </td>
                <td width="20%" style="vertical-align:bottom; text-align:center;">
                  <span style="display:block; width:42px; height:42px; border-radius:50%; border:2px solid rgba(255,255,255,0.28); margin:0 auto 8px; line-height:42px; text-align:center; color:rgba(255,255,255,0.75); font-size:20px; font-weight:300;">+</span>
                  <span style="font-size:12px; color:rgba(255,255,255,0.55); font-family:'Segoe UI',Arial,Helvetica,sans-serif;">{{ $passData['qr_scan_hint'] ?? 'Tap to scan' }}</span>
                </td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td colspan="2" align="right" style="padding-top:4px; font-size:100px; line-height:0.85; font-weight:900; letter-spacing:-6px; color:rgba(255,255,255,0.06); font-family:'Segoe UI',Arial,Helvetica,sans-serif;" aria-hidden="true">QR</td>
        </tr>
      </table>
    </td>
  </tr>
</table>
