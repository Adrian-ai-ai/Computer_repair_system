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
        if (!Schema::hasColumn('jobs', 'is_under_warranty')) {
            $table->boolean('is_under_warranty')->default(false);
        }
    });
}

public function down(): void
{
    Schema::table('jobs', function (Blueprint $table) {
        if (Schema::hasColumn('jobs', 'is_under_warranty')) {
            $table->dropColumn('is_under_warranty');
        }
    });
}

};
