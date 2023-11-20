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
        Schema::table('account_ledgers', function (Blueprint $table) {
            $table->unsignedBigInteger('sale_product_id')->after('sale_id')->nullable();
            $table->unsignedBigInteger('purchase_product_id')->after('purchase_id')->nullable();
            $table->foreign('sale_product_id')->references('id')->on('sale_products')->onDelete('cascade');
            $table->foreign('purchase_product_id')->references('id')->on('purchase_products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('account_ledgers', function (Blueprint $table) {
            $table->dropForeign(['sale_product_id']);
            $table->dropColumn('sale_product_id');
            $table->dropForeign(['purchase_product_id']);
            $table->dropColumn('purchase_product_id');
        });
    }
};
