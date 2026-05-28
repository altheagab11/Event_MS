<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PortalAccountCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $fullName,
        public string $roleLabel,
        public string $email,
        public string $temporaryPassword,
        public string $resetUrl
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Event MS Portal Account Credentials',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.portal-account-created',
        );
    }
}
