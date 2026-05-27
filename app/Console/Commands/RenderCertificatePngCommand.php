<?php

namespace App\Console\Commands;

use App\Services\EventCertificateImageService;
use Illuminate\Console\Command;

class RenderCertificatePngCommand extends Command
{
    protected $signature = 'certificate:render-png {--payload= : Base64-encoded JSON payload with certificate fields}';

    protected $description = 'Render a certificate PNG to stdout (base64) for email delivery when the web PHP process lacks GD';

    public function handle(EventCertificateImageService $certificateImageService): int
    {
        $payload = $this->decodePayload((string) $this->option('payload'));
        if ($payload === null) {
            $this->error('Invalid or missing --payload.');

            return self::FAILURE;
        }

        if (! extension_loaded('gd') || ! function_exists('imagecreatetruecolor')) {
            $this->error('GD is not available in this PHP process.');

            return self::FAILURE;
        }

        $image = $certificateImageService->generate(
            fullName: $payload['full_name'],
            eventName: $payload['event_name'],
            certificateLabel: $payload['certificate_label'],
            eventEndDate: $payload['event_end_date'],
            type: $payload['type'],
        );

        if (($image['mime'] ?? '') !== 'image/png' || ($image['bytes'] ?? '') === '') {
            $this->error('Certificate renderer did not return PNG bytes.');

            return self::FAILURE;
        }

        $this->output->write(base64_encode($image['bytes']));

        return self::SUCCESS;
    }

    /**
     * @return array{full_name: string, event_name: string, certificate_label: string, event_end_date: string, type: string}|null
     */
    private function decodePayload(string $encoded): ?array
    {
        if ($encoded === '') {
            return null;
        }

        $json = base64_decode($encoded, true);
        if ($json === false) {
            return null;
        }

        $payload = json_decode($json, true);
        if (! is_array($payload)) {
            return null;
        }

        foreach (['full_name', 'event_name', 'certificate_label', 'event_end_date', 'type'] as $key) {
            if (! isset($payload[$key]) || ! is_string($payload[$key]) || trim($payload[$key]) === '') {
                return null;
            }
        }

        return [
            'full_name' => $payload['full_name'],
            'event_name' => $payload['event_name'],
            'certificate_label' => $payload['certificate_label'],
            'event_end_date' => $payload['event_end_date'],
            'type' => $payload['type'],
        ];
    }
}
