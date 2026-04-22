<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registration Update</title>
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
          Your registration for <strong>{{ $eventName }}</strong> was reviewed by the admin team and was not approved at this time.
        </p>
        <p style="margin:0 0 14px; font-size:15px; line-height:1.55; color:#2e4672;">
          You may register again in the future if a new opportunity is available.
        </p>
        <p style="margin:0; font-size:12px; color:#60789f; line-height:1.5;">
          This is an automated notification from NU Lipa EMS.
        </p>
      </td>
    </tr>
  </table>
</body>

</html>
