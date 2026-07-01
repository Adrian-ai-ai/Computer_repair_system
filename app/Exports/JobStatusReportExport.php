<?php

namespace App\Exports;

use App\Models\Job;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Font;

class JobStatusReportExport implements FromCollection, WithHeadings, WithStyles, WithColumnWidths
{
    protected $jobs;
    protected $reportType;
    protected $recipient;
    protected $dateRange;
    protected $summary;

    public function __construct($jobs, $reportType, $recipient, $dateRange = null, $summary = null)
    {
        $this->jobs = $jobs;
        $this->reportType = $reportType;
        $this->recipient = $recipient;
        $this->dateRange = $dateRange;
        $this->summary = $summary;
    }

    public function collection()
    {
        $data = collect();

        // Add header information
        $data->push(['Report Details']);
        $data->push(['Report Type', ucfirst($this->reportType) . ' Job Status Report']);
        $data->push(['Generated On', now()->format('F j, Y \a\t g:i A')]);
        
        if ($this->recipient && isset($this->recipient['type'])) {
            $data->push(['Report For', ucfirst($this->recipient['type'])]);
            if ($this->recipient['type'] === 'client' && isset($this->recipient['name'])) {
                $data->push(['Client Name', $this->recipient['name']]);
            }
        }

        if ($this->dateRange && isset($this->dateRange[0]) && isset($this->dateRange[1])) {
            $data->push(['Date Range', $this->dateRange[0]->format('M j, Y') . ' - ' . $this->dateRange[1]->format('M j, Y')]);
        }

        $data->push(['']); // Empty row

        // Add summary statistics
        if ($this->summary) {
            $data->push(['Summary Statistics']);
            $data->push(['Total Jobs', $this->summary['total_jobs']]);
            $data->push(['Completion Rate', $this->summary['completion_rate'] . '%']);
            
            if (isset($this->summary['jobs_in_period'])) {
                $data->push(['Jobs in Period', $this->summary['jobs_in_period']]);
            }

            $data->push(['']); // Empty row
            $data->push(['Status Breakdown']);
            foreach ($this->summary['status_breakdown'] as $status => $count) {
                $data->push([$status, $count . ' jobs']);
            }
            $data->push(['']); // Empty row
        }

        // Add job details header
        $data->push(['Job Details']);
        $data->push([
            'Job Number',
            'Client Name',
            'Device Type',
            'Brand',
            'Model',
            'Fault Description',
            'Status',
            'Received By',
            'Created Date',
            'Last Updated'
        ]);

        // Add job data
        foreach ($this->jobs as $job) {
            $data->push([
                $job->job_number,
                $job->client->first_name . ' ' . $job->client->last_name,
                $job->device_type,
                $job->brand,
                $job->model,
                $job->fault_description,
                $job->status,
                $job->receiver ? $job->receiver->name : 'N/A',
                $job->created_at->format('M j, Y g:i A'),
                $job->updated_at->format('M j, Y g:i A')
            ]);

            // Add status history if available
            if ($job->statusHistory && $job->statusHistory->count() > 0) {
                $data->push(['Status History:']);
                foreach ($job->statusHistory->take(3) as $history) {
                    $data->push([
                        '  - ' . $history->status,
                        $history->user ? $history->user->name : 'Unknown',
                        $history->changed_at->format('M j, Y g:i A')
                    ]);
                }
                $data->push(['']); // Empty row after status history
            }
        }

        return $data;
    }

    public function headings(): array
    {
        // Headings are handled in the collection() method for better formatting
        return [];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style for main headers
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 16,
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
            ],
            
            // Style for section headers
            'A1:A1000' => [
                'font' => [
                    'bold' => true,
                ],
            ],

            // Style for job details header row
            'A20:J20' => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4472C4'],
                ],
            ],

            // Wrap text for long descriptions
            'F' => [
                'alignment' => [
                    'wrapText' => true,
                ],
            ],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 15, // Job Number
            'B' => 20, // Client Name
            'C' => 12, // Device Type
            'D' => 12, // Brand
            'E' => 15, // Model
            'F' => 30, // Fault Description
            'G' => 12, // Status
            'H' => 15, // Received By
            'I' => 20, // Created Date
            'J' => 20, // Last Updated
        ];
    }
}
