<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RegistrationRejectedMail extends Mailable
{
  use Queueable, SerializesModels;

  public function __construct(
    public readonly string $fullName,
    public readonly string $eventName
  ) {
  }

  public function envelope(): Envelope
  {
    return new Envelope(
      subject: 'NU Lipa EMS Registration Update'
    );
  }

  public function content(): Content
  {
    return new Content(
      view: 'emails.registration-rejected'
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
