<?php

namespace App\Mail;

use App\Models\SalesOrderDirect;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

use Illuminate\Contracts\Queue\ShouldQueue;

class NewSalesOrderDirectMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $order;

    /**
     * Create a new message instance.
     */
    public function __construct(SalesOrderDirect $order)
    {
        $this->order = $order;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Pesanan Baru: Direct Sales #' . $this->order->id,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.new_sales_order_direct',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
