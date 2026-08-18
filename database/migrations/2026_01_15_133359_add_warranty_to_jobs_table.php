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
    Schema::table('jobs', function (Blueprint $table) {
        $table->enum('warranty_status', ['In Warranty', 'Out of Warranty', 'Unknown'])
              ->default('Unknown')
              ->after('status');

        $table->date('warranty_expiry_date')->nullable()->after('warranty_status');
        $table->date('purchase_date')->nullable();
        $table->boolean('is_under_warranty')->default(false);
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jobs', function (Blueprint $table) {
            //
        });
    }
};
