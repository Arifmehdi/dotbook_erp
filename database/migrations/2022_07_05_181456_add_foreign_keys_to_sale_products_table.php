<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToSaleProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sale_products', function (Blueprint $table) {
            $table->foreign(['stock_branch_id'])->references(['id'])->on('branches')->onDelete('CASCADE');
            $table->foreign(['product_id'])->references(['id'])->on('products')->onDelete('CASCADE');
            $table->foreign(['sale_id'])->references(['id'])->on('sales')->onDelete('CASCADE');
            $table->foreign(['stock_warehouse_id'])->references(['id'])->on('warehouses')->onDelete('CASCADE');
            $table->foreign(['product_variant_id'])->references(['id'])->on('product_variants')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sale_products', function (Blueprint $table) {
            $table->dropForeign('sale_products_stock_branch_id_foreign');
            $table->dropForeign('sale_products_product_id_foreign');
            $table->dropForeign('sale_products_sale_id_foreign');
            $table->dropForeign('sale_products_stock_warehouse_id_foreign');
            $table->dropForeign('sale_products_product_variant_id_foreign');
        });
    }
}
