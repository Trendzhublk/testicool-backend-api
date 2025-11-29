<?php

namespace App\Mail;

use App\Models\Address;
use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address as MailAddress;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class PaymentStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public Address $address;
    public Collection $orderLines;
    public ?Payment $payment;
    public string $status;
    public ?string $reason;

    public function __construct(Address $address, Collection $orderLines, ?Payment $payment, string $status, ?string $reason = null)
    {
        $this->address = $address;
        $this->orderLines = $orderLines;
        $this->payment = $payment;
        $this->status = $status;
        $this->reason = $reason;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->status === 'succeeded'
                ? "Payment received for order {$this->address->order_no}"
                : "Payment issue for order {$this->address->order_no}",
            replyTo: [
                new MailAddress('support@testicool.co.uk', 'Testicool Support'),
            ],
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.payment.status'
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
