@php
  $verb = ($certificateType ?? 'attendance') === 'participation'
      ? 'has successfully participated in'
      : 'has attended';
  $footer = ($certificateType ?? 'attendance') === 'participation'
      ? 'Awarded in recognition of attendance and completed event evaluation.'
      : 'Awarded in recognition of verified event attendance.';
  $issued = 'Issued on '.now()->format('F j, Y');
  $certId = strtoupper(\Illuminate\Support\Str::substr(hash('sha256', $fullName.$eventName.($certificateType ?? 'attendance')), 0, 12));
@endphp
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:800px; border-collapse:collapse; background:#1d4f9c;">
  <tr>
    <td style="padding:8px;">
      <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border-collapse:collapse; background:#123768;">
        <tr>
          <td style="padding:4px;">
            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border-collapse:collapse; background:linear-gradient(180deg,#ffffff 0%,#f8fbff 55%,#f3f6fb 100%);">
              <tr>
                <td style="padding:28px 32px 24px; background:transparent;">

                  <table role="presentation" width="100%" cellspacing="0" cellpadding="0" aria-hidden="true">
                    <tr>
                      <td align="center" style="padding:0; font-size:0; line-height:0;">
                        <div style="font-size:180px; line-height:1; font-weight:800; letter-spacing:22px; color:#123768; opacity:0.05; font-family:'Segoe UI',Arial,Helvetica,sans-serif; mso-line-height-rule:exactly;">EMS</div>
                      </td>
                    </tr>
                  </table>

                  <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin-top:-150px;">
                    <tr>
                      <td width="56" valign="top" style="font-size:22px; line-height:1; color:#60a5fa; font-family:'Segoe UI',Arial,Helvetica,sans-serif;">&#9670;</td>
                      <td align="center" valign="top" style="padding:0 8px;">
                        <p style="margin:0 0 6px; font-size:11px; letter-spacing:4px; text-transform:uppercase; color:#60789f; font-family:'Segoe UI',Arial,Helvetica,sans-serif;">Event Management System</p>
                        <p style="margin:0 0 14px; font-size:13px; letter-spacing:6px; text-transform:uppercase; color:#123768; font-weight:700; font-family:'Segoe UI',Arial,Helvetica,sans-serif;">Official Certificate</p>
                        <h1 style="margin:0 0 16px; font-size:30px; line-height:1.2; font-weight:700; color:#0b1f3f; font-family:Georgia,'Times New Roman',serif; font-style:italic;">
                          {{ $certificateLabel }}
                        </h1>
                        <table role="presentation" cellspacing="0" cellpadding="0" align="center" style="margin:0 auto 22px;">
                          <tr>
                            <td style="width:80px; height:2px; background:#60a5fa; font-size:0; line-height:0;">&nbsp;</td>
                            <td style="padding:0 12px; font-size:14px; color:#0052c9; font-family:'Segoe UI',Arial,Helvetica,sans-serif;">&#9670;</td>
                            <td style="width:80px; height:2px; background:#60a5fa; font-size:0; line-height:0;">&nbsp;</td>
                          </tr>
                        </table>
                      </td>
                      <td width="56" align="right" valign="top" style="font-size:22px; line-height:1; color:#60a5fa; font-family:'Segoe UI',Arial,Helvetica,sans-serif;">&#9670;</td>
                    </tr>
                  </table>

                  <p style="margin:0 0 10px; text-align:center; font-size:16px; color:#516a92; font-family:Georgia,'Times New Roman',serif;">This is to certify that</p>

                  <p style="margin:0 0 14px; text-align:center; font-size:38px; line-height:1.15; font-weight:700; color:#0b1f3f; font-family:Georgia,'Times New Roman',serif; font-style:italic; word-break:break-word;">
                    {{ $fullName }}
                  </p>

                  <p style="margin:0 0 8px; text-align:center; font-size:16px; color:#516a92; font-family:Georgia,'Times New Roman',serif;">{{ $verb }}</p>

                  <p style="margin:0 0 10px; text-align:center; font-size:26px; line-height:1.25; font-weight:700; color:#0052c9; font-family:'Segoe UI',Arial,Helvetica,sans-serif; word-break:break-word;">
                    {{ $eventName }}
                  </p>

                  <p style="margin:0 0 20px; text-align:center; font-size:15px; color:#2e4672; font-family:'Segoe UI',Arial,Helvetica,sans-serif;">
                    held and concluded on <strong>{{ $eventEndDate }}</strong>
                  </p>

                  <p style="margin:0 0 28px; text-align:center; font-size:14px; line-height:1.6; color:#60789f; font-family:'Segoe UI',Arial,Helvetica,sans-serif; font-style:italic;">
                    {{ $footer }}
                  </p>

                  <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin-bottom:24px;">
                    <tr>
                      <td width="50%" align="center" valign="bottom" style="padding-top:36px; border-top:1px solid #8eb6ff;">
                        <p style="margin:8px 0 0; font-size:11px; letter-spacing:1px; text-transform:uppercase; color:#60789f; font-family:'Segoe UI',Arial,Helvetica,sans-serif;">Event Organizer</p>
                      </td>
                      <td width="50%" align="center" valign="bottom" style="padding-top:36px; border-top:1px solid #8eb6ff;">
                        <p style="margin:8px 0 0; font-size:11px; letter-spacing:1px; text-transform:uppercase; color:#60789f; font-family:'Segoe UI',Arial,Helvetica,sans-serif;">EMS Authorized</p>
                      </td>
                    </tr>
                  </table>

                  <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border-top:1px solid #dde6f3; padding-top:14px;">
                    <tr>
                      <td style="font-size:11px; color:#60789f; font-family:'Segoe UI',Arial,Helvetica,sans-serif;">{{ $issued }}</td>
                      <td align="right" style="font-size:11px; color:#60789f; font-family:'Segoe UI',Arial,Helvetica,sans-serif;">
                        Certificate No. <strong style="color:#123768; letter-spacing:0.5px;">{{ $certId }}</strong>
                      </td>
                    </tr>
                  </table>

                </td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
