<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class QuotationEmail extends Mailable
{
    use Queueable;

    public $quotation;
    public $recipientType;
    public $clientName;

    public function __construct($quotation, $recipientType = 'client')
    {
        $this->quotation = $quotation;
        $this->recipientType = $recipientType;
        $this->clientName = isset($quotation->first_name) && isset($quotation->last_name) 
            ? $quotation->first_name . ' ' . $quotation->last_name 
            : 'Valued Client';
    }

    public function envelope(): Envelope
    {
        $subject = match($this->recipientType) {
            'client' => "Quotation #{$this->quotation->id} for Your Device Repair",
            'admin' => "New Quotation Created: #{$this->quotation->id} - {$this->clientName}",
            'staff' => "Quotation Created: #{$this->quotation->id} - {$this->clientName}",
            'admin_request' => "ACTION REQUIRED: Quotation Request from Technician - #{$this->quotation->id}",
            'manager_request' => "ACTION REQUIRED: Quotation Request from Technician - #{$this->quotation->id}",
            default => "Quotation #{$this->quotation->id}",
        };

        return new Envelope(
            subject: $subject,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.quotations.send',
            with: [
                'quotation' => $this->quotation,
                'recipientType' => $this->recipientType,
                'clientName' => $this->clientName,
            ]
        );
    }

    public function attachments(): array
    {
        return [
            Attachment::fromData(fn () => $this->generateQuotationPDF(), "quotation-{$this->quotation->id}.pdf")
                ->withMime('application/pdf'),
        ];
    }

    private function generateQuotationPDF()
    {
        // Fetch quotation items
        $items = DB::table('quotation_items')
            ->where('quotation_id', $this->quotation->id)
            ->get();

        // Fetch job data for additional information
        $jobData = null;
        if ($this->quotation->job_number) {
            $jobData = DB::table('jobs')
                ->leftJoin('clients', 'jobs.client_id', '=', 'clients.id')
                ->select('jobs.*', 'clients.phone as client_phone')
                ->where('jobs.job_number', $this->quotation->job_number)
                ->first();
        }

        // Generate PDF
        $pdf = Pdf::loadView('quotations.pdf', [
            'quotation' => $this->quotation,
            'items' => $items,
            'recipientType' => $this->recipientType,
            'clientName' => $this->clientName,
            'jobData' => $jobData
        ]);

        return $pdf->output();
    }
}
