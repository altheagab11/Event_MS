<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EventEvaluationReminderMail extends Mailable
{
  use Queueable, SerializesModels;

  public function __construct(
    public readonly string $eventName,
    public readonly string $fullName,
    public readonly string $eventEndDate,
    public readonly string $evaluationUrl
  ) {
  }

  public function envelope(): Envelope
  {
    return new Envelope(
      subject: 'NU Lipa EMS Event Evaluation Reminder'
    );
  }

  public function content(): Content
  {
    return new Content(
      view: 'emails.event-evaluation-reminder'
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
