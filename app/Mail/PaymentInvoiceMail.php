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
use Barryvdh\DomPDF\Facade\Pdf;

class PaymentInvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    public Address $address;
    public Collection $orderLines;
    public ?Payment $payment;

    public function __construct(Address $address, Collection $orderLines, ?Payment $payment)
    {
        $this->address = $address;
        $this->orderLines = $orderLines;
        $this->payment = $payment;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Invoice for order {$this->address->order_no}",
            replyTo: [
                new MailAddress('support@testicool.co.uk', 'Testicool Support'),
            ],
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.payment.invoice'
        );
    }

    public function attachments(): array
    {
        $pdf = Pdf::loadView('emails.payment.invoice-pdf', [
            'address' => $this->address,
            'orderLines' => $this->orderLines,
            'payment' => $this->payment,
        ])->setPaper('a4');

        return [
            \Illuminate\Mail\Mailables\Attachment::fromData(fn() => $pdf->output(), "invoice-{$this->address->order_no}.pdf")
                ->withMime('application/pdf'),
        ];
    }
}
