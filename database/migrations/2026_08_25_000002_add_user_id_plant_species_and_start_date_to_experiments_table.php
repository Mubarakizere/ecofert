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
        Schema::table('experiments', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('experiment_id')->constrained('users')->onDelete('cascade');
            $table->string('plant_species', 100)->nullable()->after('fertilizer_type');
            $table->date('start_date')->nullable()->after('plant_species');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('experiments', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['user_id', 'plant_species', 'start_date']);
        });
    }
};
