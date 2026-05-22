<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ $certificateLabel }}</title>
</head>

<body style="margin:0; padding:24px; background:#f3f6fb; font-family:Arial, Helvetica, sans-serif; color:#132f61;">
  <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:640px; margin:0 auto; background:#ffffff; border:1px solid #dde6f3; border-radius:12px; overflow:hidden;">
    <tr>
      <td style="padding:22px 24px; background:#173b74; color:#ffffff; font-size:20px; font-weight:700;">Event Management System</td>
    </tr>
    <tr>
      <td style="padding:22px 24px;">
        <p style="margin:0 0 12px; font-size:15px;">Hi {{ $fullName }},</p>
        <p style="margin:0 0 14px; font-size:15px; line-height:1.55; color:#2e4672;">
          Thank you for participating in <strong>{{ $eventName }}</strong> (concluded on <strong>{{ $eventEndDate }}</strong>).
          Your <strong>{{ $certificateLabel }}</strong> has been issued based on your event attendance
          @if ($certificateLabel === 'Certificate of Participation')
            and completed evaluation feedback.
          @else
            record.
          @endif
        </p>

        <div style="margin:18px 0; padding:16px; border:1px solid #d5e2f5; border-radius:10px; background:#f8fbff;">
          <p style="margin:0; font-size:14px; font-weight:700; color:#173b74;">{{ $certificateLabel }}</p>
          <p style="margin:8px 0 0; font-size:13px; color:#516a92; line-height:1.5;">
            This certificate confirms your eligibility according to the post-event attendance and evaluation rules for this event.
          </p>
        </div>

        <p style="margin:0; font-size:12px; color:#60789f; line-height:1.5;">
          If you have questions about your certificate, please contact the event organizer.
        </p>
      </td>
    </tr>
  </table>
</body>

</html>
