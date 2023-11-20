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
        Schema::table('daily_stock_products', function (Blueprint $table) {
            $table->unsignedBigInteger('unit_id')->after('unit')->nullable();
            $table->foreign('unit_id')->references('id')->on('units')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('daily_stock_products', function (Blueprint $table) {
            $table->dropIndex(['unit_id']);
            $table->dropColumn('unit_id');
        });
    }
};
