<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RegistrationVerificationCodeMail extends Mailable
{
  use Queueable, SerializesModels;

  public function __construct(
    public readonly string $eventName,
    public readonly string $fullName,
    public readonly string $code,
    public readonly string $expiresAt
  ) {
  }

  public function envelope(): Envelope
  {
    return new Envelope(
      subject: 'NU Lipa EMS Registration Verification Code'
    );
  }

  public function content(): Content
  {
    return new Content(
      view: 'emails.registration-verification-code'
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
