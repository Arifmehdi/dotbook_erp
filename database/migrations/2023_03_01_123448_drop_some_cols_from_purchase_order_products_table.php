<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchase_order_products', function (Blueprint $table) {
            $table->dropColumn('tax_id');
            $table->dropColumn('unit_tax');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchase_order_products', function (Blueprint $table) {
            $table->unsignedBigInteger('tax_id')->after('subtotal')->nullable();
            $table->decimal('unit_tax', 8, 2)->after('unit_tax_percent')->nullable();
        });
    }
};
