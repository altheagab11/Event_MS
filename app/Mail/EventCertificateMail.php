<?php

namespace App\Mail;

use App\Services\EventCertificateImageService;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\HtmlString;

class EventCertificateMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @param  array{bytes: string, mime: string, filename: string}  $certificateImage
     */
    public function __construct(
        public readonly string $eventName,
        public readonly string $fullName,
        public readonly string $certificateLabel,
        public readonly string $eventEndDate,
        public readonly string $certificateType,
        public readonly string $hostedBy,
        array $certificateImage,
    ) {
        $this->certificateImage = $this->resolveCertificateImage($certificateImage);
    }

    /** @var array{bytes: string, mime: string, filename: string} */
    public readonly array $certificateImage;

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Event Management System — '.$this->certificateLabel
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.event-certificate',
        );
    }

    /**
     * @return \Closure(array): HtmlString
     */
    protected function buildView()
    {
        return function (array $data): HtmlString {
            $viewData = array_merge($this->buildViewData(), $data);
            $image = $this->certificateImage;

            if (
                ($image['mime'] ?? '') !== 'image/png'
                || ($image['bytes'] ?? '') === ''
            ) {
                $image = app(EventCertificateImageService::class)->generateForDelivery(
                    fullName: $this->fullName,
                    eventName: $this->eventName,
                    certificateLabel: $this->certificateLabel,
                    eventEndDate: $this->eventEndDate,
                    type: $this->certificateType,
                    hostedBy: $this->hostedBy,
                );
            }

            if (
                ($image['mime'] ?? '') === 'image/png'
                && ($image['bytes'] ?? '') !== ''
                && isset($viewData['message'])
            ) {
                $viewData['certificatePreviewSrc'] = $viewData['message']->embedData(
                    $image['bytes'],
                    'certificate-inline.png',
                    'image/png'
                );
            }

            return new HtmlString(
                view('emails.event-certificate', $viewData)->render()
            );
        };
    }

    /**
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        $image = $this->certificateImage;

        if (($image['bytes'] ?? '') === '') {
            return [];
        }

        if (($image['mime'] ?? '') !== 'image/png') {
            $image = app(EventCertificateImageService::class)->generateForDelivery(
                fullName: $this->fullName,
                eventName: $this->eventName,
                certificateLabel: $this->certificateLabel,
                eventEndDate: $this->eventEndDate,
                type: $this->certificateType,
                hostedBy: $this->hostedBy,
            );
        }

        if (($image['bytes'] ?? '') === '') {
            return [];
        }

        return [
            Attachment::fromData(
                fn () => $image['bytes'],
                $image['filename']
            )->withMime($image['mime']),
        ];
    }

    /**
     * @param  array{bytes: string, mime: string, filename: string}  $certificateImage
     * @return array{bytes: string, mime: string, filename: string}
     */
    private function resolveCertificateImage(array $certificateImage): array
    {
        if (
            ($certificateImage['mime'] ?? '') === 'image/png'
            && ($certificateImage['bytes'] ?? '') !== ''
        ) {
            return $certificateImage;
        }

        return app(EventCertificateImageService::class)->generateForDelivery(
            fullName: $this->fullName,
            eventName: $this->eventName,
            certificateLabel: $this->certificateLabel,
            eventEndDate: $this->eventEndDate,
            type: $this->certificateType,
            hostedBy: $this->hostedBy,
        );
    }
}
