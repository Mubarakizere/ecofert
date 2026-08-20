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
        Schema::create('approved_formulations', function (Blueprint $table) {
            $table->id('formula_id');
            $table->enum('target_waste_type', ['Banana Peels', 'Eggshells', 'Coffee Grounds']);
            $table->text('preparation_steps')->comment('Validated step-by-step preparation guide.');
            $table->text('application_guidance')->comment('Rules for applying the fertilizer safely.');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('approved_formulations');
    }
};
