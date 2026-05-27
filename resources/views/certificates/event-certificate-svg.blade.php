<svg xmlns="http://www.w3.org/2000/svg" width="1200" height="848" viewBox="0 0 1200 848" role="img" aria-label="{{ $certificateLabel }}">
  <defs>
    <linearGradient id="panelGradient" x1="0%" y1="0%" x2="0%" y2="100%">
      <stop offset="0%" stop-color="#ffffff" />
      <stop offset="55%" stop-color="#f8fbff" />
      <stop offset="100%" stop-color="#f3f6fb" />
    </linearGradient>
  </defs>

  {{-- Outer frame (matches email: #1d4f9c → #123768 → gradient panel) --}}
  <rect width="1200" height="848" fill="#1d4f9c" />
  <rect x="8" y="8" width="1184" height="832" fill="#123768" />
  <rect x="12" y="12" width="1176" height="824" fill="url(#panelGradient)" />

  {{-- Watermark background layer (drawn first, behind all content) --}}
  <text
    x="600"
    y="430"
    fill="#123768"
    opacity="0.05"
    font-family="Segoe UI, Arial, Helvetica, sans-serif"
    font-size="240"
    font-weight="800"
    letter-spacing="24"
    text-anchor="middle"
  >EMS</text>

  {{-- Corner diamonds --}}
  <text x="56" y="88" fill="#60a5fa" font-family="Segoe UI, Arial, Helvetica, sans-serif" font-size="22">&#9670;</text>
  <text x="1144" y="88" fill="#60a5fa" font-family="Segoe UI, Arial, Helvetica, sans-serif" font-size="22" text-anchor="end">&#9670;</text>

  {{-- Header --}}
  <text x="600" y="78" fill="#60789f" font-family="Segoe UI, Arial, Helvetica, sans-serif" font-size="14" letter-spacing="4" text-anchor="middle">EVENT MANAGEMENT SYSTEM</text>
  <text x="600" y="108" fill="#123768" font-family="Segoe UI, Arial, Helvetica, sans-serif" font-size="16" letter-spacing="6" font-weight="700" text-anchor="middle">OFFICIAL CERTIFICATE</text>
  <text x="600" y="168" fill="#0b1f3f" font-family="Georgia, 'Times New Roman', serif" font-size="36" font-style="italic" font-weight="700" text-anchor="middle">{{ $certificateLabel }}</text>

  {{-- Title divider --}}
  <line x1="440" y1="192" x2="540" y2="192" stroke="#60a5fa" stroke-width="2" />
  <text x="600" y="200" fill="#0052c9" font-family="Segoe UI, Arial, Helvetica, sans-serif" font-size="18" text-anchor="middle">&#9670;</text>
  <line x1="660" y1="192" x2="760" y2="192" stroke="#60a5fa" stroke-width="2" />

  {{-- Body --}}
  <text x="600" y="268" fill="#516a92" font-family="Georgia, 'Times New Roman', serif" font-size="20" text-anchor="middle">This is to certify that</text>
  <text x="600" y="338" fill="#0b1f3f" font-family="Georgia, 'Times New Roman', serif" font-size="46" font-style="italic" font-weight="700" text-anchor="middle">{{ $fullName }}</text>
  <text x="600" y="388" fill="#516a92" font-family="Georgia, 'Times New Roman', serif" font-size="20" text-anchor="middle">{{ $verb }}</text>
  <text x="600" y="438" fill="#0052c9" font-family="Segoe UI, Arial, Helvetica, sans-serif" font-size="32" font-weight="700" text-anchor="middle">{{ $eventName }}</text>
  <text x="600" y="478" fill="#2e4672" font-family="Segoe UI, Arial, Helvetica, sans-serif" font-size="18" text-anchor="middle">held and concluded on {{ $eventEndDate }}</text>
  <text x="600" y="540" fill="#60789f" font-family="Segoe UI, Arial, Helvetica, sans-serif" font-size="16" font-style="italic" text-anchor="middle">{{ $footer }}</text>

  {{-- Signatory: host name on line, role label below (no signature image) --}}
  @if (! empty($hostedByName))
    <text x="320" y="628" fill="#123768" font-family="Segoe UI, Arial, Helvetica, sans-serif" font-size="12" font-weight="600" text-anchor="middle">{{ $hostedByName }}</text>
  @endif
  <line x1="120" y1="640" x2="520" y2="640" stroke="#8eb6ff" stroke-width="1" />
  <text x="320" y="668" fill="#60789f" font-family="Segoe UI, Arial, Helvetica, sans-serif" font-size="12" letter-spacing="1" text-anchor="middle">EVENT ORGANIZER</text>

  <line x1="680" y1="640" x2="1080" y2="640" stroke="#8eb6ff" stroke-width="1" />
  <text x="880" y="668" fill="#60789f" font-family="Segoe UI, Arial, Helvetica, sans-serif" font-size="12" letter-spacing="1" text-anchor="middle">EMS AUTHORIZED</text>

  {{-- Footer --}}
  <line x1="44" y1="728" x2="1156" y2="728" stroke="#dde6f3" stroke-width="1" />
  <text x="44" y="762" fill="#60789f" font-family="Segoe UI, Arial, Helvetica, sans-serif" font-size="13">{{ $issued }}</text>
  <text x="1156" y="762" font-family="Segoe UI, Arial, Helvetica, sans-serif" font-size="13" text-anchor="end">
    <tspan fill="#60789f">Certificate No. </tspan><tspan fill="#123768" font-weight="700">{{ $certId }}</tspan>
  </text>
</svg>
