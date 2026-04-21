<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Event Evaluation Reminder</title>
</head>

<body style="margin:0; padding:24px; background:#f3f6fb; font-family:Arial, Helvetica, sans-serif; color:#132f61;">
  <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:640px; margin:0 auto; background:#ffffff; border:1px solid #dde6f3; border-radius:12px; overflow:hidden;">
    <tr>
      <td style="padding:22px 24px; background:#173b74; color:#ffffff; font-size:20px; font-weight:700;">NU Lipa EMS</td>
    </tr>
    <tr>
      <td style="padding:22px 24px;">
        <p style="margin:0 0 12px; font-size:15px;">Hi {{ $fullName }},</p>
        <p style="margin:0 0 14px; font-size:15px; line-height:1.55; color:#2e4672;">
          Your registered event <strong>{{ $eventName }}</strong> already finished on <strong>{{ $eventEndDate }}</strong>.
          We would appreciate your feedback through a short evaluation.
        </p>

        <div style="margin:18px 0; padding:16px; border:1px solid #d5e2f5; border-radius:10px; background:#f8fbff;">
          <p style="margin:0 0 12px; font-size:13px; color:#516a92; line-height:1.5;">
            Please click the button below to open the event page and submit your evaluation.
          </p>
          <a href="{{ $evaluationUrl }}" style="display:inline-block; text-decoration:none; background:#173b74; color:#ffffff; padding:11px 16px; border-radius:9px; font-size:13px; font-weight:700;">
            Evaluate This Event
          </a>
        </div>

        <p style="margin:0; font-size:12px; color:#60789f; line-height:1.5;">
          If the button does not work, copy and paste this link into your browser:<br>
          <a href="{{ $evaluationUrl }}" style="color:#173b74; word-break:break-all;">{{ $evaluationUrl }}</a>
        </p>
      </td>
    </tr>
  </table>
</body>

</html>
