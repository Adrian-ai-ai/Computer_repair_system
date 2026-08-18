<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Mark the problematic migrations as completed without trying to re-run them
        $migrations = [
            '2026_01_26_153049_create_quotations_table',
            '2026_01_26_153057_create_quotation_items_table',
            '2026_01_29_164000_fix_quotations_job_numbers',
        ];

        $batch = DB::table('migrations')->max('batch') ?? 0;
        
        foreach ($migrations as $migration) {
            // Check if it's already marked as completed
            $exists = DB::table('migrations')
                ->where('migration', $migration)
                ->exists();

            if (!$exists) {
                DB::table('migrations')->insert([
                    'migration' => $migration,
                    'batch' => $batch + 1,
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove the marked migrations
        DB::table('migrations')
            ->whereIn('migration', [
                '2026_01_26_153049_create_quotations_table',
                '2026_01_26_153057_create_quotation_items_table',
                '2026_01_29_164000_fix_quotations_job_numbers',
            ])
            ->delete();
    }
};
