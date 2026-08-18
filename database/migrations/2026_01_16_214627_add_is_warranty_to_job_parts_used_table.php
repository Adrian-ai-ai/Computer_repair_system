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
        Schema::table('job_parts_used', function (Blueprint $table) {
<<<<<<< HEAD
            $table->boolean('is_warranty')->default(false)->after('quantity_used');
=======
            // Only add if it doesn't already exist
            if (!Schema::hasColumn('job_parts_used', 'is_warranty')) {
                $table->boolean('is_warranty')->default(false)->after('quantity_used');
            }
>>>>>>> 814c0e3a080a93b0a4c40958610f5493345a9fd8
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_parts_used', function (Blueprint $table) {
            $table->dropColumn('is_warranty');
        });
    }
};
