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
        Schema::create('fertilizer_batches', function (Blueprint $table) {
            $table->id('batch_id');
            $table->string('batch_code', 50)->unique();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('formulation_id')->constrained('approved_formulations', 'formula_id')->onDelete('cascade');
            $table->enum('status', ['aging', 'ready', 'applied', 'discarded'])->default('aging');
            $table->json('used_ingredients')->comment('Json breakdown of ingredients used');
            $table->date('start_date');
            $table->date('estimated_ready_date');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fertilizer_batches');
    }
};
