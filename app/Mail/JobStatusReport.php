<?php

namespace App\Mail;

use App\Models\Job;
use App\Models\User;
use App\Exports\JobStatusReportExport;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class JobStatusReport extends Mailable
{
    use Queueable, SerializesModels;

   public $jobs;
public $summary;


    public function __construct($jobs, $type, $recipient, $sender, $dateRange = null)
    {
        $this->jobs = $jobs;
        $this->reportType = $type;
        $this->recipient = $recipient;
        $this->sender = $sender;
        $this->dateRange = $dateRange;

        $totalJobs = $jobs->count();
        $completedJobs = $jobs->where('status', 'Delivered')->count();

        // Calculate total amount from quotations
        $totalAmount = 0;
        foreach ($jobs as $job) {
            foreach ($job->quotations as $quotation) {
                $totalAmount += $quotation->total_amount ?? 0;
            }
        }

        $this->summary = [
            'total_jobs' => $totalJobs,
            'jobs_in_period' => $totalJobs,
            'completion_rate' => $totalJobs > 0
                ? round(($completedJobs / $totalJobs) * 100, 2)
                : 0,
            'total_amount' => $totalAmount,
            'status_breakdown' => $jobs
                ->groupBy('status')
                ->map(fn ($group) => $group->count()),
        ];
    }


    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = $this->getSubject();

        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.job-status-report',
            with: [
                'jobs' => $this->jobs,
                'reportType' => $this->reportType,
                'recipient' => $this->recipient,
                'generatedBy' => $this->sender,
                'dateRange' => $this->dateRange,
                'summary' => $this->summary,
            ]);
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        $pdf = Pdf::loadView('emails.job-status-report-pdf', [
            'jobs' => $this->jobs,
            'reportType' => $this->reportType,
            'recipient' => $this->recipient,
            'generatedBy' => $this->sender,
            'dateRange' => $this->dateRange,
            'summary' => $this->summary,
        ]);

        $excel = Excel::raw(new JobStatusReportExport(
            $this->jobs,
            $this->reportType,
            $this->recipient,
            $this->dateRange,
            $this->summary
        ), \Maatwebsite\Excel\Excel::XLSX);

        return [
            \Illuminate\Mail\Mailables\Attachment::fromData(
                fn () => $pdf->output(),
                'job-status-report.pdf'
            )->withMime('application/pdf'),
            \Illuminate\Mail\Mailables\Attachment::fromData(
                fn () => $excel,
                'job-status-report.xlsx'
            )->withMime('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'),
        ];
    }

    /**
     * Get the subject line based on report type and recipient.
     */
    private function getSubject(): string
    {
        $baseSubject = 'Job Status Report';

        switch ($this->reportType) {
            case 'daily':
                $baseSubject = 'Daily Job Status Report';
                break;
            case 'weekly':
                $baseSubject = 'Weekly Job Status Report';
                break;
            case 'monthly':
                $baseSubject = 'Monthly Job Status Report';
                break;
            case 'custom':
                $baseSubject = 'Custom Job Status Report';
                break;
        }

        if ($this->recipient && isset($this->recipient['type'])) {
            switch ($this->recipient['type']) {
                case 'client':
                    $baseSubject .= ' - ' . ($this->recipient['name'] ?? 'Client');
                    break;
                case 'staff':
                case 'manager':
                    $baseSubject .= ' - ' . ucfirst($this->recipient['type']) . ' Update';
                    break;
            }
        }

        return $baseSubject;
    }

    /**
     * Get summary statistics for the report.
     */
  
}