<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DeliveryFeedbackMail extends Mailable
{
    use Queueable, SerializesModels;

    public Order $order;
    public string $feedbackUrl;

    public function __construct(Order $order, string $feedbackUrl)
    {
        $this->order = $order;
        $this->feedbackUrl = $feedbackUrl;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "We'd love your feedback on order {$this->order->tracking_number}",
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.orders.feedback'
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
