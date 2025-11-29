<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Throwable;

class ErrorReportMail extends Mailable
{
    use Queueable, SerializesModels;

    public Throwable $exception;
    public array $context;

    public function __construct(Throwable $exception, array $context = [])
    {
        $this->exception = $exception;
        $this->context = $context;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'App Error: ' . class_basename($this->exception),
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.errors.report'
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
