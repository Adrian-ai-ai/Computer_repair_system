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
        // Update quotations to use new numeric job numbers
        $quotations = Quotation::all();
        
        foreach ($quotations as $quotation) {
            // Find the corresponding job with the old job number
            $job = Job::where('job_number', $quotation->job_number)->first();
            
            if ($job) {
                // Update quotation with the new numeric job number
                $oldJobNumber = $quotation->job_number;
                $newJobNumber = $job->job_number;
                
                \Log::info("Updating quotation {$quotation->id}: {$oldJobNumber} -> {$newJobNumber}");
                
                $quotation->update(['job_number' => $newJobNumber]);
            } else {
                \Log::warning("No job found for quotation job number: {$quotation->job_number}");
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This is a complex migration to reverse, so we'll log it for manual reversal if needed
        \Log::warning("Manual reversal required for quotations job number update migration");
    }
};
