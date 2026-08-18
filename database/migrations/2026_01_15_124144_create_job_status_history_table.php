<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::create('job_status_history', function (Blueprint $table) {
        $table->id();
        $table->foreignId('job_id')->constrained()->onDelete('cascade');
        $table->string('status');
        $table->foreignId('changed_by')->constrained('users');
        $table->timestamp('changed_at')->useCurrent();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_status_history');
    }
};
