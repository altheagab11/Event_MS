@php
  $displayCode = preg_replace('/^EMS-/i', '', (string) ($passData['pass_code'] ?? ''));
  $displayCode = $displayCode !== '' ? strtoupper($displayCode) : strtoupper((string) ($passData['pass_code'] ?? ''));
@endphp
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:760px; border-collapse:separate; border-spacing:0; border-radius:22px; border:1px solid rgba(100,160,255,0.55); background-color:#123768; background-image:linear-gradient(135deg,#0b1f3f 0%,#123768 48%,#1d4f9c 100%); box-shadow:0 24px 60px rgba(0,0,0,0.22);">
  <tr>
    <td style="padding:30px 34px 26px;">
      <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
        <tr>
          <td align="center" style="padding-bottom:12px;">
            <span style="font-size:26px; font-weight:900; letter-spacing:-1px; color:rgba(255,255,255,0.95); font-family:'Segoe UI',Arial,Helvetica,sans-serif; text-shadow:0 2px 12px rgba(255,255,255,0.35);">EMS</span>
          </td>
        </tr>
        <tr>
          <td align="center" style="padding:4px 0 14px;">
            <table role="presentation" cellspacing="0" cellpadding="0" align="center" style="background:#ffffff; border-radius:22px; box-shadow:0 16px 36px rgba(52,96,150,0.22);">
              <tr>
                <td style="padding:14px;">
                  @if (! empty($passData['qr_embed']['bytes']))
                    <img src="{{ $message->embedData($passData['qr_embed']['bytes'], $passData['qr_embed']['filename'], $passData['qr_embed']['mime']) }}" alt="Event pass QR code" width="190" height="190" style="display:block; width:190px; height:190px;">
                  @endif
                </td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td align="center" style="padding-bottom:22px;">
            <span style="display:inline-block; padding:10px 20px; border-radius:999px; background:rgba(255,255,255,0.38); border:1px solid rgba(255,255,255,0.55); color:#ffffff; font-size:15px; font-weight:800; letter-spacing:2px; font-family:'Segoe UI',Arial,Helvetica,sans-serif;">{{ $displayCode }}</span>
          </td>
        </tr>
        <tr>
          <td>
            <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td style="vertical-align:bottom;">
                  <span style="font-family:'Segoe UI',Arial,Helvetica,sans-serif; font-weight:800; color:rgba(255,255,255,0.42); letter-spacing:1px;">
                    <span style="display:inline-block; min-width:52px; padding:4px 8px; border:2px solid rgba(255,255,255,0.38); font-size:18px; line-height:1; color:rgba(255,255,255,0.42);">EMS</span>
                    <span style="font-size:20px; font-weight:300; opacity:0.7; padding:0 8px;">|</span>
                    <span style="font-size:22px; letter-spacing:3px; opacity:0.85; color:rgba(255,255,255,0.42);">EVENTS</span>
                  </span>
                </td>
                <td width="52" align="right" style="vertical-align:bottom;">
                  <span style="display:inline-block; width:44px; height:44px; border-radius:50%; border:2px solid rgba(255,255,255,0.38); background:rgba(255,255,255,0.12); text-align:center; line-height:40px; color:rgba(255,255,255,0.72); font-size:16px;">&#9638;</span>
                </td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
