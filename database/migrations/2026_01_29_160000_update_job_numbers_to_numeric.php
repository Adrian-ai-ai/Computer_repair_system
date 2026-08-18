<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Job;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update existing job numbers to sequential format starting from 3901
        $jobs = Job::orderBy('id')->get();
        $startNumber = 3901;

        foreach ($jobs as $index => $job) {
            $newJobNumber = (string)($startNumber + $index);
            
            // Log the change for audit purposes
            \Log::info("Updating job ID {$job->id}: {$job->job_number} -> {$newJobNumber}");
            
            $job->update(['job_number' => $newJobNumber]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to original random format
        $jobs = Job::all();
        
        foreach ($jobs as $job) {
            $oldJobNumber = 'JOB-' . strtoupper(\Illuminate\Support\Str::random(6));
            $job->update(['job_number' => $oldJobNumber]);
        }
    }
};
