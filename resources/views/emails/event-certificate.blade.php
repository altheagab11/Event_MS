@php
  $supportEmail = (string) config('mail.from.address', 'ems.support@events.com');
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ $certificateLabel }}</title>
</head>

<body style="margin:0; padding:32px 16px; background:#e8eef7; font-family:'Segoe UI', Arial, Helvetica, sans-serif; color:#1a3358;">
  <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:800px; margin:0 auto;">
    <tr>
      <td style="padding:0 0 18px; font-size:15px; line-height:1.6;">
        <p style="margin:0 0 8px; font-size:16px; font-weight:700; color:#1a3358;">Hi {{ $fullName }},</p>
        <p style="margin:0; font-size:14px; color:#3d5a80;">
          Your <strong>{{ $certificateLabel }}</strong> for <strong>{{ $eventName }}</strong> (concluded {{ $eventEndDate }}) is ready. Your certificate is shown below; a copy is also attached to this email for printing.
        </p>
      </td>
    </tr>

    <tr>
      <td style="padding-bottom:16px; line-height:0;">
        @if (! empty($certificatePreviewSrc))
          <img
            src="{{ $certificatePreviewSrc }}"
            alt="{{ $certificateLabel }} for {{ $fullName }}"
            width="800"
            style="display:block; width:100%; max-width:800px; height:auto; border:0; border-radius:4px;"
          >
        @else
          <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:800px; border:1px solid #c5d4e8; border-radius:8px; background:#ffffff;">
            <tr>
              <td style="padding:28px 24px; font-size:14px; line-height:1.6; color:#3d5a80; text-align:center; font-family:'Segoe UI',Arial,Helvetica,sans-serif;">
                <p style="margin:0 0 10px; font-size:16px; font-weight:700; color:#1a3358;">{{ $certificateLabel }}</p>
                <p style="margin:0;">Your certificate is attached to this email. Open the attachment to view or print the full certificate.</p>
              </td>
            </tr>
          </table>
        @endif
      </td>
    </tr>

    <tr>
      <td style="padding:4px 2px 0; font-size:12px; color:#60789f; line-height:1.55; font-family:'Segoe UI',Arial,Helvetica,sans-serif;">
        If you have questions, contact the event organizer or EMS Support at
        <a href="mailto:{{ $supportEmail }}" style="color:#0052c9; text-decoration:none;">{{ $supportEmail }}</a>.
      </td>
    </tr>
  </table>
</body>

</html>
