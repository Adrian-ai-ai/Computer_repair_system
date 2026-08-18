<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('job_status_history', function (Blueprint $table) {
            $table->foreignId('job_id')->constrained()->onDelete('cascade');
            $table->string('status');
            $table->foreignId('changed_by')->constrained('users');
            $table->timestamp('changed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_status_history', function (Blueprint $table) {
            //
        });
    }
};
