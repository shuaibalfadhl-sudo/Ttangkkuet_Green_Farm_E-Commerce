<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AvailableProductsMail extends Mailable
{
    use Queueable, SerializesModels;

    public $availableProducts;

    public function __construct($availableProducts)
    {
        $this->availableProducts = $availableProducts;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Available Products Update',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.products.available_all',
            with: [
                'availableProducts' => $this->availableProducts
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
