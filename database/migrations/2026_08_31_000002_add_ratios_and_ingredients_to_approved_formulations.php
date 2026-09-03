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
        Schema::table('approved_formulations', function (Blueprint $table) {
            $table->string('title', 150)->nullable()->after('formula_id');
            $table->json('required_ingredients')->nullable()->after('target_waste_type');
            $table->decimal('yield_quantity', 5, 2)->default(1.00)->after('required_ingredients');
            $table->string('yield_unit', 20)->default('L')->after('yield_quantity');
            $table->unsignedInteger('fermentation_days')->default(7)->after('yield_unit');
            $table->string('npk_ratio', 50)->nullable()->after('fermentation_days');
            $table->string('primary_nutrients', 150)->nullable()->after('npk_ratio');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('approved_formulations', function (Blueprint $table) {
            $table->dropColumn([
                'title',
                'required_ingredients',
                'yield_quantity',
                'yield_unit',
                'fermentation_days',
                'npk_ratio',
                'primary_nutrients',
            ]);
        });
    }
};
