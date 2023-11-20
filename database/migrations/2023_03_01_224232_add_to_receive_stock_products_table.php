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
        Schema::table('receive_stock_products', function (Blueprint $table) {
            $table->unsignedBigInteger('purchase_order_product_id')->after('receive_stock_id')->nullable();
            $table->foreign('purchase_order_product_id')->references('id')->on('purchase_order_products')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('receive_stock_products', function (Blueprint $table) {
            $table->dropForeign(['purchase_order_product_id']);
            $table->dropColumn('purchase_order_product_id');
        });
    }
};
