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
    Schema::create('jobs', function (Blueprint $table) {
        $table->id();
        $table->string('job_number')->unique();
        $table->foreignId('client_id')->constrained()->onDelete('cascade');
        $table->string('device_type'); // Computer or Printer
        $table->string('brand')->nullable();
        $table->string('model')->nullable();
        $table->string('serial_number')->nullable();
        $table->text('fault_description');
        $table->string('status')->default('Received');
        $table->foreignId('received_by')->nullable()->constrained('users');
        $table->timestamp('received_at')->useCurrent();
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobs');
    }
};
