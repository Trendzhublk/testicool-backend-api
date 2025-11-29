<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class OrderStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public Order $order;
    public Collection $items;

    public function __construct(Order $order, Collection $items)
    {
        $this->order = $order;
        $this->items = $items;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Order {$this->order->tracking_number} status: {$this->order->status}",
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.orders.status'
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
