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
        Schema::table('jobs', function (Blueprint $table) {
            $table->index('status');
            $table->index('is_under_warranty');
        });

        Schema::table('stock_movements', function (Blueprint $table) {
            $table->index('product_id');
            $table->index('created_at');
});
Schema::table('job_parts_used', function (Blueprint $table) {
    $table->foreign('job_id')
        ->references('id')->on('jobs')
        ->onDelete('cascade');

    $table->foreign('product_id')
        ->references('id')->on('products')
        ->onDelete('restrict');
        });
    }
};
