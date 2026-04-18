<?php

namespace App\Mail;

use App\Models\SalesOrderDirect;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SalesOrderDirectCustomerMail extends Mailable implements ShouldQueue
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
            subject: 'Terima Kasih! Pesanan Anda #' . $this->order->id . ' Sedang Diproses',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.sales_order_direct_customer',
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
