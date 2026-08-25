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
        Schema::table('waste_logs', function (Blueprint $table) {
            $table->decimal('quantity', 8, 2)->nullable()->after('waste_type');
            $table->enum('unit', ['kg', 'g', 'items'])->default('kg')->after('quantity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('waste_logs', function (Blueprint $table) {
            $table->dropColumn(['quantity', 'unit']);
        });
    }
};
