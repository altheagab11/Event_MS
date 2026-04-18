<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Digital Pass Preview</title>
</head>

<body style="margin:0; padding:24px; background:#f3f6fb; font-family:Arial, Helvetica, sans-serif; color:#132f61;">
  <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:660px; margin:0 auto; background:#ffffff; border:1px solid #dde6f3; border-radius:12px; overflow:hidden;">
    <tr>
      <td style="padding:22px 24px; background:#173b74; color:#ffffff; font-size:20px; font-weight:700;">NU Lipa EMS Digital ID (Demo)</td>
    </tr>
    <tr>
      <td style="padding:22px 24px;">
        <p style="margin:0 0 12px; font-size:15px;">Hi {{ $passData['full_name'] }},</p>
        <p style="margin:0 0 16px; font-size:15px; color:#2e4672; line-height:1.55;">
          Your event registration has been verified. This is a sample digital pass preview for demo/testing.
        </p>

        <div style="border:1px solid #d5e2f5; border-radius:12px; overflow:hidden; margin-bottom:18px;">
          <div style="background:linear-gradient(135deg, #183d7e, #23569d); color:#ffffff; padding:14px 16px;">
            <div style="font-size:11px; letter-spacing:1px; opacity:.85;">NU LIPA EVENT PASS</div>
            <div style="font-size:20px; font-weight:700; margin-top:6px;">{{ $passData['event_name'] }}</div>
          </div>
          <div style="padding:14px 16px;">
            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="font-size:14px; color:#344e7b;">
              <tr>
                <td style="padding:4px 0; width:140px;"><strong>Attendee</strong></td>
                <td style="padding:4px 0;">{{ $passData['full_name'] }}</td>
              </tr>
              <tr>
                <td style="padding:4px 0;"><strong>Email</strong></td>
                <td style="padding:4px 0;">{{ $passData['email'] }}</td>
              </tr>
              <tr>
                <td style="padding:4px 0;"><strong>Event Date</strong></td>
                <td style="padding:4px 0;">{{ $passData['event_date'] }}</td>
              </tr>
              <tr>
                <td style="padding:4px 0;"><strong>Venue</strong></td>
                <td style="padding:4px 0;">{{ $passData['location'] }}</td>
              </tr>
              <tr>
                <td style="padding:4px 0;"><strong>Pass Code</strong></td>
                <td style="padding:4px 0; font-weight:700; color:#15386f;">{{ $passData['pass_code'] }}</td>
              </tr>
            </table>
          </div>
        </div>

        <p style="margin:0; font-size:12px; color:#60789f; line-height:1.5;">
          This email is a demo output while scanner/API-based digital ID generation is still pending implementation.
        </p>
      </td>
    </tr>
  </table>
</body>

</html>
