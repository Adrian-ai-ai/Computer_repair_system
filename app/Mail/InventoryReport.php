<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade\Pdf;

class InventoryReport extends Mailable
{
    use Queueable, SerializesModels;

    public $inventoryData;
    public $subject;
    public $customMessage;

    /**
     * Create a new message instance.
     */
    public function __construct($inventoryData, $subject, $customMessage = null)
    {
        $this->inventoryData = $inventoryData;
        $this->subject = $subject;
        $this->customMessage = $customMessage;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.inventory.report',
            with: [
                'inventoryData' => $this->inventoryData,
                'customMessage' => $this->customMessage,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [
            Attachment::fromData(fn() => Pdf::loadView('pdf.inventory-report', $this->inventoryData)->output(), 'inventory-report.pdf')
                ->withMime('application/pdf'),
        ];
    }
}
