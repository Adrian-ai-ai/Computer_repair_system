<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Quotation;
use App\Models\Job;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Create mapping from old job numbers to new numeric ones based on job ID
        $jobMapping = [];
        $jobs = Job::all(['id', 'job_number']);
        
        // Since we updated jobs sequentially starting from 3901, we can map by ID
        foreach ($jobs as $job) {
            $jobMapping[$job->id] = $job->job_number;
        }
        
        // Update quotations based on their original job relationships
        // We need to check if there are any relationships or create a manual mapping
        $quotations = Quotation::all();
        
        foreach ($quotations as $quotation) {
            // For this specific case, we'll map the old job numbers to new ones
            // This is based on the chronological order when jobs were created
            $mapping = [
                'JOB-SQC7BR' => '3901', // First job
                'JOB-HMBP0V' => '3902', // Second job  
                'JOB-TYD2KI' => '3903', // Third job
            ];
            
            if (isset($mapping[$quotation->job_number])) {
                $oldJobNumber = $quotation->job_number;
                $newJobNumber = $mapping[$quotation->job_number];
                
                \Log::info("Updating quotation {$quotation->id}: {$oldJobNumber} -> {$newJobNumber}");
                
                $quotation->update(['job_number' => $newJobNumber]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverse the mapping
        $quotations = Quotation::all();
        
        $reverseMapping = [
            '3901' => 'JOB-SQC7BR',
            '3902' => 'JOB-HMBP0V', 
            '3903' => 'JOB-TYD2KI',
        ];
        
        foreach ($quotations as $quotation) {
            if (isset($reverseMapping[$quotation->job_number])) {
                $quotation->update(['job_number' => $reverseMapping[$quotation->job_number]]);
            }
        }
    }
};
