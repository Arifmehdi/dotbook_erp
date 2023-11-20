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
        Schema::table('sale_products', function (Blueprint $table) {
            $table->decimal('do_delivered_qty', 22, 2)->change()->default(0);
            $table->decimal('do_left_qty', 22, 2)->change()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sale_products', function (Blueprint $table) {
            $table->decimal('do_delivered_qty', 8, 2)->change()->default(0);
            $table->decimal('do_left_qty', 8, 2)->change()->default(0);
        });
    }
};
