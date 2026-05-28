<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Account Created</title>
</head>
<body style="margin:0;padding:0;background:#f1f5f9;font-family:Arial,sans-serif;color:#0f172a;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="padding:24px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="620" cellspacing="0" cellpadding="0" style="max-width:620px;width:100%;background:#ffffff;border:1px solid #e2e8f0;border-radius:12px;overflow:hidden;">
                    <tr>
                        <td style="padding:24px;background:#0f172a;color:#f8fafc;">
                            <h1 style="margin:0;font-size:20px;line-height:1.4;">Your portal account is ready</h1>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:24px;">
                            <p style="margin:0 0 14px;">Hello {{ $fullName }},</p>
                            <p style="margin:0 0 14px;">
                                A {{ $roleLabel }} account was created for you in the Event Management System.
                            </p>
                            <p style="margin:0 0 4px;"><strong>Email:</strong> {{ $email }}</p>
                            <p style="margin:0 0 18px;"><strong>Temporary Password:</strong> {{ $temporaryPassword }}</p>
                            <p style="margin:0 0 16px;">
                                For security, please reset your password immediately using the button below.
                            </p>
                            <p style="margin:0 0 24px;">
                                <a href="{{ $resetUrl }}" style="display:inline-block;padding:12px 18px;border-radius:8px;background:#2563eb;color:#ffffff;text-decoration:none;font-weight:700;">
                                    Reset My Password
                                </a>
                            </p>
                            <p style="margin:0;color:#475569;font-size:13px;line-height:1.6;">
                                If the button does not work, copy and paste this URL into your browser:<br>
                                <span style="word-break:break-all;">{{ $resetUrl }}</span>
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
