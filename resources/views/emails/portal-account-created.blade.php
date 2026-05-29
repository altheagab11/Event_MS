<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Portal Account Created</title>
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
          A <strong>{{ $roleLabel }}</strong> account was created for you in the Event Management System. Use the credentials below to sign in, then reset your password right away.
        </p>

        <div style="margin:18px 0; padding:14px 16px; border:2px dashed rgba(136,170,210,0.35); border-radius:10px; background:#f8fbff; text-align:center;">
          <div style="font-size:11px; letter-spacing:1px; color:#60789f; margin-bottom:10px;">PORTAL CREDENTIALS</div>
          <p style="margin:0 0 6px; font-size:14px; color:#2e4672; line-height:1.5;">
            <strong>Email:</strong> {{ $email }}
          </p>
          <p style="margin:0 0 14px; font-size:14px; color:#2e4672; line-height:1.5;">
            <strong>Temporary Password:</strong> {{ $temporaryPassword }}
          </p>
          <p style="margin:0 0 12px; font-size:13px; color:#516a92; line-height:1.5;">
            For security, please reset your password immediately using the button below.
          </p>
          <a href="{{ $resetUrl }}" style="display:inline-block; text-decoration:none; background:#0052c9; color:#ffffff; padding:11px 18px; border-radius:9px; font-size:13px; font-weight:700; box-shadow:0 2px 8px rgba(0,82,201,0.18);">
            Reset My Password
          </a>
        </div>

        <p style="margin:0; font-size:12px; color:#60789f; line-height:1.5;">
          If the button does not work, copy and paste this link into your browser:<br>
          <a href="{{ $resetUrl }}" style="color:#0052c9; word-break:break-all;">{{ $resetUrl }}</a>
        </p>
      </td>
    </tr>
  </table>
</body>

</html>
