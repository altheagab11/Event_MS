<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Symfony\Component\Process\Process;
use Throwable;

class EventCertificateImageService
{
    private const WIDTH = 1200;

    private const HEIGHT = 848;

    /**
     * @return array{bytes: string, mime: string, filename: string}
     */
    public function generate(
        string $fullName,
        string $eventName,
        string $certificateLabel,
        string $eventEndDate,
        string $type = 'attendance',
        string $hostedBy = '',
    ): array {
        return $this->generateForDelivery($fullName, $eventName, $certificateLabel, $eventEndDate, $type, $hostedBy);
    }

    /**
     * PNG when GD is available (email preview + attachment match). SVG fallback otherwise.
     *
     * @return array{bytes: string, mime: string, filename: string}
     */
    public function generateForDelivery(
        string $fullName,
        string $eventName,
        string $certificateLabel,
        string $eventEndDate,
        string $type = 'attendance',
        string $hostedBy = '',
    ): array {
        if (extension_loaded('gd') && function_exists('imagecreatetruecolor')) {
            return $this->generatePng($fullName, $eventName, $certificateLabel, $eventEndDate, $type, $hostedBy);
        }

        $png = $this->generatePngViaCli($fullName, $eventName, $certificateLabel, $eventEndDate, $type, $hostedBy);
        if ($png !== null) {
            return $png;
        }

        $svg = $this->generateSvg($fullName, $eventName, $certificateLabel, $eventEndDate, $type, $hostedBy);
        $converted = $this->convertSvgBytesToPng($svg['bytes']);
        if ($converted !== null) {
            return [
                'bytes' => $converted,
                'mime' => 'image/png',
                'filename' => $this->buildFilename($certificateLabel, $fullName, 'png'),
            ];
        }

        Log::warning('PHP GD extension is disabled. Event certificates will use SVG attachment and HTML email preview.');

        return $svg;
    }

    /**
     * @return array{bytes: string, mime: string, filename: string}|null
     */
    private function generatePngViaCli(
        string $fullName,
        string $eventName,
        string $certificateLabel,
        string $eventEndDate,
        string $type,
        string $hostedBy = '',
    ): ?array {
        if (PHP_SAPI === 'cli') {
            return null;
        }

        $payload = base64_encode(json_encode([
            'full_name' => $fullName,
            'event_name' => $eventName,
            'certificate_label' => $certificateLabel,
            'event_end_date' => $eventEndDate,
            'type' => $type,
            'hosted_by' => $hostedBy,
        ], JSON_THROW_ON_ERROR));

        try {
            $process = new Process([
                PHP_BINARY,
                base_path('artisan'),
                'certificate:render-png',
                '--payload='.$payload,
            ], base_path(), null, null, 60);

            $process->run();

            if (! $process->isSuccessful()) {
                Log::warning('CLI certificate PNG render failed.', [
                    'exit_code' => $process->getExitCode(),
                    'error' => trim($process->getErrorOutput()),
                ]);

                return null;
            }

            $bytes = base64_decode(trim($process->getOutput()), true);
            if ($bytes === false || $bytes === '') {
                return null;
            }

            return [
                'bytes' => $bytes,
                'mime' => 'image/png',
                'filename' => $this->buildFilename($certificateLabel, $fullName, 'png'),
            ];
        } catch (Throwable $exception) {
            Log::warning('CLI certificate PNG render exception.', [
                'error' => $exception->getMessage(),
            ]);

            return null;
        }
    }

    private function convertSvgBytesToPng(string $svg): ?string
    {
        if (! extension_loaded('imagick') || ! class_exists(\Imagick::class)) {
            return null;
        }

        try {
            $imagick = new \Imagick;
            $imagick->setBackgroundColor(new \ImagickPixel('white'));
            $imagick->readImageBlob($svg);
            $imagick->setImageFormat('png');
            $imagick->setImageAlphaChannel(\Imagick::ALPHACHANNEL_REMOVE);

            return $imagick->getImageBlob();
        } catch (Throwable $exception) {
            Log::warning('Imagick SVG to PNG conversion failed.', [
                'error' => $exception->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * @return array{bytes: string, mime: string, filename: string}
     */
    private function generatePng(
        string $fullName,
        string $eventName,
        string $certificateLabel,
        string $eventEndDate,
        string $type,
        string $hostedBy = '',
    ): array {
        $image = imagecreatetruecolor(self::WIDTH, self::HEIGHT);
        imagealphablending($image, true);

        $borderOuter = imagecolorallocate($image, 29, 79, 156);
        $borderMid = imagecolorallocate($image, 18, 55, 104);
        $panelTop = imagecolorallocate($image, 255, 255, 255);
        $panelMid = imagecolorallocate($image, 248, 251, 255);
        $panelBot = imagecolorallocate($image, 243, 246, 251);
        $navyDark = imagecolorallocate($image, 11, 31, 63);
        $navyMid = imagecolorallocate($image, 18, 55, 104);
        $navyLight = imagecolorallocate($image, 29, 79, 156);
        $accent = imagecolorallocate($image, 0, 82, 201);
        $accentSoft = imagecolorallocate($image, 96, 165, 250);
        $textMuted = imagecolorallocate($image, 96, 120, 159);
        $textBody = imagecolorallocate($image, 46, 70, 114);
        $white = imagecolorallocate($image, 255, 255, 255);
        $lineSoft = imagecolorallocate($image, 221, 230, 243);

        imagefilledrectangle($image, 0, 0, self::WIDTH, self::HEIGHT, $borderOuter);
        imagefilledrectangle($image, 8, 8, self::WIDTH - 9, self::HEIGHT - 9, $borderMid);
        $this->fillGradientBand($image, 12, 12, self::WIDTH - 24, self::HEIGHT - 24, $panelTop, $panelMid, $panelBot);

        $fontRegular = $this->resolveFontPath();
        $fontBold = $this->resolveFontPath(bold: true);
        $fontItalic = $this->resolveFontPath(italic: true);

        $watermarkColor = imagecolorallocatealpha($image, 18, 55, 104, 115);
        $this->writeCenteredText($image, 400, 'EMS', $fontBold, 130, $watermarkColor);

        $this->writeText($image, 56, 72, chr(0xE2).chr(0x97).chr(0xA6), $fontRegular, 18, $accentSoft);
        $this->writeText($image, self::WIDTH - 72, 72, chr(0xE2).chr(0x97).chr(0xA6), $fontRegular, 18, $accentSoft);

        $this->writeCenteredText($image, 62, 'EVENT MANAGEMENT SYSTEM', $fontRegular, 11, $textMuted);
        $this->writeCenteredText($image, 92, 'OFFICIAL CERTIFICATE', $fontBold, 13, $navyMid);

        $titleY = 152;
        $this->writeCenteredText($image, $titleY, $certificateLabel, $fontItalic ?? $fontBold, 30, $navyDark);
        imageline($image, 440, 176, 540, 176, $accentSoft);
        imageline($image, 660, 176, 760, 176, $accentSoft);

        $this->writeCenteredText($image, 252, 'This is to certify that', $fontRegular, 17, $textMuted);

        $recipient = $this->truncateText($fullName, 42);
        $this->writeCenteredText($image, 322, $recipient, $fontItalic ?? $fontBold, 40, $navyDark);

        $verb = $type === 'participation' ? 'has successfully participated in' : 'has attended';
        $this->writeCenteredText($image, 372, $verb, $fontRegular, 17, $textMuted);

        $eventLine = $this->truncateText($eventName, 56);
        $this->writeCenteredText($image, 422, $eventLine, $fontBold, 26, $accent);

        $dateLine = 'held and concluded on '.$eventEndDate;
        $this->writeCenteredText($image, 462, $dateLine, $fontRegular, 15, $textBody);

        $footer = $type === 'participation'
            ? 'Awarded in recognition of attendance and completed event evaluation.'
            : 'Awarded in recognition of verified event attendance.';

        $this->writeCenteredText($image, 524, $footer, $fontItalic ?? $fontRegular, 13, $textMuted);

        $hostedByName = trim($hostedBy);
        if ($hostedByName !== '') {
            $this->writeCenteredText(
                $image,
                608,
                $this->truncateText($hostedByName, 48),
                $fontRegular,
                12,
                $navyMid,
                320
            );
        }

        imageline($image, 120, 624, 520, 624, $accentSoft);
        $this->writeCenteredText($image, 652, 'EVENT ORGANIZER', $fontRegular, 10, $textMuted, 320);

        imageline($image, 680, 624, 1080, 624, $accentSoft);
        $this->writeCenteredText($image, 652, 'EMS AUTHORIZED', $fontRegular, 10, $textMuted, 880);

        imageline($image, 44, 712, self::WIDTH - 44, 712, $lineSoft);

        $issued = 'Issued on '.now()->format('F j, Y');
        $this->writeText($image, 44, 746, $issued, $fontRegular, 12, $textMuted);

        $certIdLabel = 'Certificate No. ';
        $certId = strtoupper(Str::substr(hash('sha256', $fullName.$eventName.$type), 0, 12));
        $this->writeTextRight($image, self::WIDTH - 44, 746, $certIdLabel.$certId, $fontRegular, 12, $textMuted);

        ob_start();
        imagepng($image, null, 6);
        $bytes = (string) ob_get_clean();
        imagedestroy($image);

        return [
            'bytes' => $bytes,
            'mime' => 'image/png',
            'filename' => $this->buildFilename($certificateLabel, $fullName, 'png'),
        ];
    }

    /**
     * @return array{bytes: string, mime: string, filename: string}
     */
    private function generateSvg(
        string $fullName,
        string $eventName,
        string $certificateLabel,
        string $eventEndDate,
        string $type,
        string $hostedBy = '',
    ): array {
        $verb = $type === 'participation' ? 'has successfully participated in' : 'has attended';
        $footer = $type === 'participation'
            ? 'Awarded in recognition of attendance and completed event evaluation.'
            : 'Awarded in recognition of verified event attendance.';
        $issued = 'Issued on '.now()->format('F j, Y');
        $certId = strtoupper(Str::substr(hash('sha256', $fullName.$eventName.$type), 0, 12));

        $svg = view('certificates.event-certificate-svg', [
            'fullName' => $this->escapeSvg($fullName),
            'eventName' => $this->escapeSvg($eventName),
            'certificateLabel' => $this->escapeSvg($certificateLabel),
            'eventEndDate' => $this->escapeSvg($eventEndDate),
            'verb' => $this->escapeSvg($verb),
            'footer' => $this->escapeSvg($footer),
            'issued' => $this->escapeSvg($issued),
            'certId' => $this->escapeSvg($certId),
            'hostedByName' => $this->escapeSvg(trim($hostedBy)),
        ])->render();

        return [
            'bytes' => $svg,
            'mime' => 'image/svg+xml',
            'filename' => $this->buildFilename($certificateLabel, $fullName, 'svg'),
        ];
    }

    private function buildFilename(string $certificateLabel, string $fullName, string $extension): string
    {
        $basename = trim($certificateLabel.' - '.$fullName);
        $basename = preg_replace('/[\\\\\\/:*?"<>|]/', '-', $basename) ?? '';
        $basename = trim($basename);

        return ($basename !== '' ? $basename : 'event-certificate').'.'.$extension;
    }

    private function resolveFontPath(bool $bold = false, bool $italic = false): ?string
    {
        if ($italic) {
            $candidates = ['C:\\Windows\\Fonts\\georgiai.ttf', 'C:\\Windows\\Fonts\\timesi.ttf'];
        } elseif ($bold) {
            $candidates = ['C:\\Windows\\Fonts\\arialbd.ttf', 'C:\\Windows\\Fonts\\georgiab.ttf'];
        } else {
            $candidates = ['C:\\Windows\\Fonts\\georgia.ttf', 'C:\\Windows\\Fonts\\arial.ttf'];
        }

        foreach ($candidates as $path) {
            if (is_readable($path)) {
                return $path;
            }
        }

        return null;
    }

    /**
     * @param  resource  $image
     */
    private function writeText($image, int $x, int $y, string $text, ?string $font, int $size, int $color): void
    {
        if ($font !== null && function_exists('imagettftext')) {
            imagettftext($image, $size, 0, $x, $y + $size, $color, $font, $text);

            return;
        }

        imagestring($image, $size >= 28 ? 5 : ($size >= 18 ? 4 : 3), $x, $y, $text, $color);
    }

    /**
     * @param  resource  $image
     */
    private function writeTextRight($image, int $rightX, int $y, string $text, ?string $font, int $size, int $color): void
    {
        if ($font !== null && function_exists('imagettfbbox')) {
            $box = imagettfbbox($size, 0, $font, $text);
            $textWidth = abs($box[2] - $box[0]);
            imagettftext($image, $size, 0, $rightX - $textWidth, $y + $size, $color, $font, $text);

            return;
        }

        $fontId = 3;
        $textWidth = imagefontwidth($fontId) * strlen($text);
        imagestring($image, $fontId, $rightX - $textWidth, $y, $text, $color);
    }

    /**
     * @param  resource  $image
     */
    private function writeCenteredText($image, int $y, string $text, ?string $font, int $size, int $color, ?int $centerX = null): void
    {
        $centerX ??= (int) (self::WIDTH / 2);

        if ($font !== null && function_exists('imagettfbbox')) {
            $box = imagettfbbox($size, 0, $font, $text);
            $textWidth = abs($box[2] - $box[0]);
            $x = (int) ($centerX - ($textWidth / 2));
            imagettftext($image, $size, 0, $x, $y + $size, $color, $font, $text);

            return;
        }

        $fontId = $size >= 34 ? 5 : ($size >= 24 ? 4 : 3);
        $textWidth = imagefontwidth($fontId) * strlen($text);
        $x = (int) ($centerX - ($textWidth / 2));
        imagestring($image, $fontId, $x, $y, $text, $color);
    }

    /**
     * @param  resource  $image
     */
    private function fillGradientBand($image, int $x, int $y, int $width, int $height, int $start, int $middle, int $end): void
    {
        for ($row = 0; $row < $height; $row++) {
            $ratio = $height <= 1 ? 0 : $row / ($height - 1);
            $color = $ratio < 0.5
                ? $this->blendColor($start, $middle, $ratio * 2)
                : $this->blendColor($middle, $end, ($ratio - 0.5) * 2);
            imageline($image, $x, $y + $row, $x + $width, $y + $row, $color);
        }
    }

    private function blendColor(int $from, int $to, float $ratio): int
    {
        $ratio = max(0, min(1, $ratio));
        $fromR = ($from >> 16) & 0xFF;
        $fromG = ($from >> 8) & 0xFF;
        $fromB = $from & 0xFF;
        $toR = ($to >> 16) & 0xFF;
        $toG = ($to >> 8) & 0xFF;
        $toB = $to & 0xFF;

        $red = (int) round($fromR + (($toR - $fromR) * $ratio));
        $green = (int) round($fromG + (($toG - $fromG) * $ratio));
        $blue = (int) round($fromB + (($toB - $fromB) * $ratio));

        return ($red << 16) | ($green << 8) | $blue;
    }

    private function truncateText(string $text, int $maxLength): string
    {
        $text = trim($text);
        if (strlen($text) <= $maxLength) {
            return $text;
        }

        return rtrim(substr($text, 0, $maxLength - 1)).'…';
    }

    private function escapeSvg(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES | ENT_XML1, 'UTF-8');
    }
}