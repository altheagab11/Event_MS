<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registration Verification Code</title>
</head>

<body style="margin:0; padding:24px; background:#f3f6fb; font-family:Arial, Helvetica, sans-serif; color:#132f61;">
  <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:620px; margin:0 auto; background:#ffffff; border:1px solid #dde6f3; border-radius:12px; overflow:hidden;">
    <tr>
      <td style="padding:22px 24px; background:linear-gradient(135deg,#0b1f3f 0%,#123768 48%,#1d4f9c 100%); color:#ffffff; font-size:20px; font-weight:700;">EMS</td>
    </tr>
    <tr>
      <td style="padding:22px 24px;">
        <p style="margin:0 0 12px; font-size:15px;">Hi {{ $fullName }},</p>
        <p style="margin:0 0 14px; font-size:15px; line-height:1.55; color:#2e4672;">
          You requested to authenticate your registration for <strong>{{ $eventName }}</strong>. Use the verification code below to confirm your identity — it expires shortly for security reasons.
        </p>

        <div style="margin:18px 0; padding:14px 16px; border:2px dashed rgba(136,170,210,0.35); border-radius:10px; background:#f8fbff; text-align:center;">
          <div style="font-size:11px; letter-spacing:1px; color:#60789f; margin-bottom:6px;">VERIFICATION CODE</div>
          <div style="font-size:30px; letter-spacing:6px; font-weight:800; color:#0052c9;">{{ $code }}</div>
          <div style="margin-top:8px; font-size:12px; color:#5f7397;">Expires at: {{ $expiresAt }}</div>
        </div>

        <!-- CTA removed: users should enter the verification code manually -->

        <p style="margin:0; font-size:13px; color:#60789f; line-height:1.5;">
          If you did not request this code, you can safely ignore this message.
        </p>
      </td>
    </tr>
  </table>
</body>

</html>
