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
    Schema::create('stock_movements', function (Blueprint $table) {
        $table->id();
        $table->foreignId('product_id')->constrained()->onDelete('cascade');
        $table->string('movement_type'); // IN or OUT
        $table->integer('quantity');
        $table->string('reference_type')->nullable(); // purchase, repair, sale
        $table->unsignedBigInteger('reference_id')->nullable();
        $table->foreignId('created_by')->constrained('users');
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
};
