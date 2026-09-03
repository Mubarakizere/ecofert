<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('waste_logs', function (Blueprint $table) {
            $table->enum('transaction_type', ['added', 'used'])->default('added')->after('unit');
        });
    }

    public function down(): void
    {
        Schema::table('waste_logs', function (Blueprint $table) {
            $table->dropColumn('transaction_type');
        });
    }
};
