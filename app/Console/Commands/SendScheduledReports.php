<?php

namespace App\Console\Commands;

use App\Http\Controllers\ReportsController;
use Illuminate\Console\Command;

class SendScheduledReports extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reports:send-scheduled {type=daily : Type of report to send (daily|weekly)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send scheduled email reports to staff and managers';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $type = $this->argument('type');

        $this->info("Sending {$type} reports...");

        $controller = new ReportsController();

        try {
            if ($type === 'daily') {
                $response = $controller->sendDailyReport();
                $this->info('Daily reports sent successfully');
            } elseif ($type === 'weekly') {
                $response = $controller->sendWeeklyReport();
                $this->info('Weekly reports sent successfully');
            } else {
                $this->error("Invalid report type: {$type}. Use 'daily' or 'weekly'");
                return 1;
            }
        } catch (\Exception $e) {
            $this->error("Failed to send {$type} reports: " . $e->getMessage());
            return 1;
        }

        return 0;
    }
}