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
        // Migration commented out to prevent duplicate table error.
        // Schema::create('quotations', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('job_number');
        //     $table->unsignedBigInteger('client_id');
        //     $table->decimal('subtotal', 10, 2);
        //     $table->decimal('tax', 10, 2)->default(0);
        //     $table->decimal('discount', 10, 2)->default(0);
        //     $table->decimal('total_amount', 10, 2);
        //     $table->string('status')->default('sent');
        //     $table->date('valid_until');
        //     $table->unsignedBigInteger('created_by');
        //     $table->timestamps();
        //     $table->foreign('client_id')->references('id')->on('clients');
        //     $table->foreign('created_by')->references('id')->on('users');
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Schema::dropIfExists('quotations');
    }
};
