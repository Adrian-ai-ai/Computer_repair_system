<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Direct database update to avoid timestamp issues
        $mapping = [
            'JOB-SQC7BR' => '3901',
            'JOB-HMBP0V' => '3902', 
            'JOB-TYD2KI' => '3903',
        ];
        
        foreach ($mapping as $oldJobNumber => $newJobNumber) {
            DB::table('quotations')
                ->where('job_number', $oldJobNumber)
                ->update(['job_number' => $newJobNumber]);
                
            \Log::info("Updated quotations with job number: {$oldJobNumber} -> {$newJobNumber}");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverse the mapping
        $reverseMapping = [
            '3901' => 'JOB-SQC7BR',
            '3902' => 'JOB-HMBP0V', 
            '3903' => 'JOB-TYD2KI',
        ];
        
        foreach ($reverseMapping as $newJobNumber => $oldJobNumber) {
            DB::table('quotations')
                ->where('job_number', $newJobNumber)
                ->update(['job_number' => $oldJobNumber]);
                
            \Log::info("Reverted quotations with job number: {$newJobNumber} -> {$oldJobNumber}");
        }
    }
};
