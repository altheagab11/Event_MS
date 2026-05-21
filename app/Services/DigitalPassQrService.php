<?php

namespace App\Services;

use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

class DigitalPassQrService
{
    /**
     * @return array{bytes: string, mime: string, filename: string}
     */
    public function forEmailEmbed(string $content, int $size = 280): array
    {
        if (extension_loaded('gd') && function_exists('gd_info') && class_exists(\BaconQrCode\Renderer\GDLibRenderer::class)) {
            $renderer = new \BaconQrCode\Renderer\GDLibRenderer($size, 2);
            $writer = new Writer($renderer);

            return [
                'bytes' => $writer->writeString($content),
                'mime' => 'image/png',
                'filename' => 'event-pass-qr.png',
            ];
        }

        $renderer = new ImageRenderer(new RendererStyle($size), new SvgImageBackEnd);
        $writer = new Writer($renderer);

        return [
            'bytes' => $writer->writeString($content),
            'mime' => 'image/svg+xml',
            'filename' => 'event-pass-qr.svg',
        ];
    }

    public function asSvgString(string $content, int $size = 180): string
    {
        $renderer = new ImageRenderer(new RendererStyle($size), new SvgImageBackEnd);
        $writer = new Writer($renderer);

        return $writer->writeString($content);
    }
}
