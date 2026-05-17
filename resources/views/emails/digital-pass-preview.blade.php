@php
  $supportEmail = (string) config('mail.from.address', 'ems.support@events.com');
  $appUrl = (string) config('app.url', 'https://www.ems.events');
  $appHost = parse_url($appUrl, PHP_URL_HOST) ?: $appUrl;
  $viewUrl = (string) ($passData['view_url'] ?? '#');
  $downloadUrl = (string) ($passData['download_url'] ?? $viewUrl);
  $displayCode = strtoupper(preg_replace('/^EMS-/i', '', (string) ($passData['pass_code'] ?? '')));
  if ($displayCode === '') {
      $displayCode = (string) ($passData['pass_code'] ?? '');
  }
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Your EMS Event Pass</title>
</head>

<body style="margin:0; padding:24px 12px; background:#e8eef7; font-family:'Segoe UI', Arial, Helvetica, sans-serif; color:#1a3358;">
  <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:640px; margin:0 auto;">
    <tr>
      <td style="padding:0 0 18px; font-size:15px; line-height:1.6;">
        <p style="margin:0 0 8px; font-size:16px; font-weight:700;">Hi {{ $passData['full_name'] }},</p>
        <p style="margin:0; font-size:14px;">
          Your registration has been approved. Open your interactive pass below to flip the card, view the QR code on the back, and download a copy for check-in.
        </p>
      </td>
    </tr>

    <tr>
      <td style="padding-bottom:16px;">
        <a href="{{ $viewUrl }}" target="_blank" rel="noopener noreferrer" style="text-decoration:none; color:inherit; display:block;">
          <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border-radius:22px; overflow:hidden; border:1px solid rgba(100,160,255,0.55); background:linear-gradient(135deg,#0b1f3f 0%,#123768 48%,#1d4f9c 100%); box-shadow:0 16px 40px rgba(0,0,0,0.22);">
            <tr>
              <td style="padding:22px 24px 10px;">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                  <tr>
                    <td style="vertical-align:top; width:40px;">
                      <div style="width:30px; height:30px; border-radius:8px; background:#ff6b8a;"></div>
                    </td>
                    <td style="text-align:right; vertical-align:top;">
                      <span style="display:inline-block; padding:6px 12px; border-radius:999px; background:rgba(34,197,94,0.18); border:1px solid rgba(74,222,128,0.45); color:#86efac; font-size:11px; font-weight:800; letter-spacing:1px;">&#10003; CONFIRMED</span>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
            <tr>
              <td style="padding:0 24px 4px;">
                <div style="font-size:20px; font-weight:700; color:#ffffff;">{{ $passData['event_name'] }}</div>
              </td>
            </tr>
            <tr>
              <td style="padding:8px 24px 4px;">
                <div style="font-size:34px; line-height:1.08; font-weight:800; color:#ffffff;">{{ $passData['full_name'] }}</div>
              </td>
            </tr>
            <tr>
              <td style="padding:0 24px 18px;">
                <div style="font-size:15px; line-height:1.45; color:rgba(255,255,255,0.88);">{{ $passData['profile_line'] ?? 'Event Participant' }}</div>
              </td>
            </tr>
            <tr>
              <td style="padding:0 24px 22px;">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="28%" style="vertical-align:bottom; padding-right:10px;">
                      <div style="font-size:10px; font-weight:700; letter-spacing:1.2px; color:rgba(255,255,255,0.72);">VALID THRU</div>
                      <div style="margin-top:6px; font-size:22px; font-weight:600; color:#ffffff;">{{ $passData['valid_thru'] ?? 'TBA' }}</div>
                    </td>
                    <td width="52%" style="vertical-align:bottom; padding-right:10px;">
                      <div style="font-size:10px; font-weight:700; letter-spacing:1.2px; color:rgba(255,255,255,0.72);">EMAIL</div>
                      <div style="margin-top:6px; font-size:14px; font-weight:500; color:#ffffff; text-decoration:underline; word-break:break-all;">{{ $passData['email'] }}</div>
                    </td>
                    <td width="20%" style="vertical-align:bottom; text-align:right; color:rgba(255,255,255,0.55); font-size:11px;">
                      Tap to scan
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
            <tr>
              <td style="padding:0 24px 20px; text-align:center;">
                <span style="display:inline-block; padding:10px 18px; border-radius:999px; background:rgba(39,198,255,0.18); color:#27c6ff; font-size:12px; font-weight:700; letter-spacing:0.4px;">
                  Tap to open &amp; flip your pass
                </span>
              </td>
            </tr>
          </table>
        </a>
      </td>
    </tr>

    <tr>
      <td style="padding-bottom:18px; text-align:center;">
        <table role="presentation" cellspacing="0" cellpadding="0" align="center">
          <tr>
            <td style="padding:0 6px 8px;">
              <a href="{{ $viewUrl }}" target="_blank" rel="noopener noreferrer" style="display:inline-block; padding:13px 24px; border-radius:999px; background:linear-gradient(135deg,#27c6ff,#0052c9); color:#ffffff; font-size:14px; font-weight:700; text-decoration:none;">
                View &amp; Flip Pass
              </a>
            </td>
            <td style="padding:0 6px 8px;">
              <a href="{{ $downloadUrl }}" target="_blank" rel="noopener noreferrer" style="display:inline-block; padding:13px 24px; border-radius:999px; background:#ffffff; color:#0052c9; border:1px solid #8eb6ff; font-size:14px; font-weight:700; text-decoration:none;">
                Download Pass
              </a>
            </td>
          </tr>
        </table>
      </td>
    </tr>

    <tr>
      <td style="padding-bottom:16px;">
        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border-radius:22px; overflow:hidden; border:1px solid rgba(100,160,255,0.55); background:linear-gradient(135deg,#0b1f3f 0%,#123768 48%,#1d4f9c 100%); box-shadow:0 16px 40px rgba(0,0,0,0.22);">
          <tr>
            <td style="padding:28px 24px 22px; text-align:center;">
              <div style="font-size:24px; font-weight:900; color:rgba(255,255,255,0.95); letter-spacing:-1px; margin-bottom:12px;">EMS</div>
              <div style="display:inline-block; padding:14px; background:#ffffff; border-radius:20px; box-shadow:0 12px 28px rgba(52,96,150,0.2);">
                @if (! empty($passData['qr_embed']['bytes']))
                  <img src="{{ $message->embedData($passData['qr_embed']['bytes'], $passData['qr_embed']['filename'], $passData['qr_embed']['mime']) }}" alt="Event pass QR code" width="200" height="200" style="display:block; width:200px; height:200px;">
                @endif
              </div>
              <div style="display:inline-block; margin-top:14px; padding:10px 18px; border-radius:999px; background:rgba(255,255,255,0.38); border:1px solid rgba(255,255,255,0.55); color:#ffffff; font-size:14px; font-weight:800; letter-spacing:2px;">
                {{ $displayCode }}
              </div>
              <p style="margin:14px 0 0; font-size:12px; color:rgba(255,255,255,0.75);">Flip the card in your browser to scan at the venue.</p>
            </td>
          </tr>
        </table>
      </td>
    </tr>

    <tr>
      <td style="padding:0 4px; font-size:12px; color:#60789f; line-height:1.5;">
        Need help? Contact EMS Support at {{ $supportEmail }} or visit {{ $appHost }}.<br>
        This is an automated message from the Event Management System. Do not share your pass code or QR with others.
      </td>
    </tr>
  </table>
</body>

</html>
