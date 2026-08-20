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
        Schema::create('growth_measurements', function (Blueprint $table) {
            $table->id('measurement_id');
            $table->foreignId('experiment_id')->constrained('experiments', 'experiment_id')->onDelete('cascade');
            $table->tinyInteger('week_number')->comment('Week 1, 2, 3, or 4.');
            $table->decimal('plant_height_cm', 5, 2)->comment('Height of the plant in cm.');
            $table->decimal('soil_pH', 3, 1)->comment('Soil pH reading.');
            $table->string('leaf_vitality', 50)->comment('Visual health rating.');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('growth_measurements');
    }
};
