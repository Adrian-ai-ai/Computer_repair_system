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
       Schema::create('repair_jobs', function (Blueprint $table) {
    $table->id();

    $table->foreignId('client_id')->constrained()->cascadeOnDelete();

    $table->string('device_type'); // laptop, printer, desktop
    $table->string('brand')->nullable();
    $table->string('model')->nullable();
    $table->string('serial_number')->nullable();

    $table->text('problem_description');
    $table->enum('status', ['received', 'diagnosing', 'repairing', 'completed', 'delivered'])
          ->default('received');

    $table->date('received_at');
    $table->date('completed_at')->nullable();

    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('repair_jobs');

    }
};
