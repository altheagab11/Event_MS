<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DigitalPassPreviewMail extends Mailable
{
  use Queueable, SerializesModels;

  /**
   * @param array<string, mixed> $passData
   */
  public function __construct(public readonly array $passData)
  {
  }

  public function envelope(): Envelope
  {
    return new Envelope(
      subject: 'NU Lipa EMS Digital ID Pass (Demo)'
    );
  }

  public function content(): Content
  {
    return new Content(
      view: 'emails.digital-pass-preview'
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
