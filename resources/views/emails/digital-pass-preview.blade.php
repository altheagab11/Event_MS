@php
  $supportEmail = (string) config('mail.from.address', 'ems.support@events.com');
  $appUrl = (string) config('app.url', 'https://www.ems.events');
  $appHost = parse_url($appUrl, PHP_URL_HOST) ?: $appUrl;
  $viewUrl = (string) ($passData['view_url'] ?? '#');
  $downloadUrl = (string) ($passData['download_url'] ?? $viewUrl);
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Your EMS Event Pass</title>
</head>

<body style="margin:0; padding:32px 16px; background:#e8eef7; font-family:'Segoe UI',Arial,Helvetica,sans-serif; color:#1a3358;">
  <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:760px; margin:0 auto;">
    <tr>
      <td style="padding:0 0 20px; font-size:15px; line-height:1.6;">
        <p style="margin:0 0 8px; font-size:16px; font-weight:700; color:#1a3358;">Hi {{ $passData['full_name'] }},</p>
        <p style="margin:0; font-size:14px; color:#3d5a80;">
          Your registration has been approved. Your digital event pass is below — present the QR code at check-in or open the interactive pass online.
        </p>
      </td>
    </tr>

    <tr>
      <td style="padding-bottom:16px;">
        @include('emails.partials.digital-pass-front')
      </td>
    </tr>

    <tr>
      <td style="padding-bottom:20px; text-align:center;">
        <table role="presentation" cellspacing="0" cellpadding="0" align="center">
          <tr>
            <td style="padding:0 6px 8px;">
              <a href="{{ $viewUrl }}" target="_blank" rel="noopener noreferrer" style="display:inline-block; padding:13px 24px; border-radius:999px; background:linear-gradient(135deg,#27c6ff,#0052c9); color:#ffffff; font-size:14px; font-weight:700; text-decoration:none; font-family:'Segoe UI',Arial,Helvetica,sans-serif;">
                View &amp; Flip Pass
              </a>
            </td>
            <td style="padding:0 6px 8px;">
              <a href="{{ $downloadUrl }}" target="_blank" rel="noopener noreferrer" style="display:inline-block; padding:13px 24px; border-radius:999px; background:#ffffff; color:#0052c9; border:1px solid #8eb6ff; font-size:14px; font-weight:700; text-decoration:none; font-family:'Segoe UI',Arial,Helvetica,sans-serif;">
                Download Pass
              </a>
            </td>
          </tr>
        </table>
      </td>
    </tr>

    <tr>
      <td style="padding-bottom:16px;">
        @include('emails.partials.digital-pass-back')
      </td>
    </tr>

    <tr>
      <td style="padding:8px 4px 0; font-size:12px; color:#60789f; line-height:1.5; font-family:'Segoe UI',Arial,Helvetica,sans-serif;">
        Need help? Contact EMS Support at <a href="mailto:{{ $supportEmail }}" style="color:#0052c9;">{{ $supportEmail }}</a> or visit <a href="{{ $appUrl }}" style="color:#0052c9;">{{ $appHost }}</a>.<br>
        This is an automated message from the Event Management System. Do not share your pass code or QR with others.
      </td>
    </tr>
  </table>
</body>

</html>
