<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToPurchaseSaleProductChainsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchase_sale_product_chains', function (Blueprint $table) {
            $table->foreign(['purchase_product_id'])->references(['id'])->on('purchase_products')->onDelete('CASCADE');
            $table->foreign(['sale_product_id'])->references(['id'])->on('sale_products')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchase_sale_product_chains', function (Blueprint $table) {
            $table->dropForeign('purchase_sale_product_chains_purchase_product_id_foreign');
            $table->dropForeign('purchase_sale_product_chains_sale_product_id_foreign');
        });
    }
}
