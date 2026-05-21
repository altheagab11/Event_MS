<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EventCertificateMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly string $eventName,
        public readonly string $fullName,
        public readonly string $certificateLabel,
        public readonly string $eventEndDate,
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'NU Lipa EMS — '.$this->certificateLabel
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.event-certificate',
        );
    }

    /**
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
