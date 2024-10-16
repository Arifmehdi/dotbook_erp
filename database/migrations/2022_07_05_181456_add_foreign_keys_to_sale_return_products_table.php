<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToSaleReturnProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sale_return_products', function (Blueprint $table) {
            $table->foreign(['sale_return_id'])->references(['id'])->on('sale_returns')->onDelete('CASCADE');
            $table->foreign(['product_id'])->references(['id'])->on('products')->onDelete('CASCADE');
            $table->foreign(['sale_product_id'])->references(['id'])->on('sale_products')->onDelete('CASCADE');
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
        Schema::table('sale_return_products', function (Blueprint $table) {
            $table->dropForeign('sale_return_products_sale_return_id_foreign');
            $table->dropForeign('sale_return_products_product_id_foreign');
            $table->dropForeign('sale_return_products_sale_product_id_foreign');
            $table->dropForeign('sale_return_products_product_variant_id_foreign');
        });
    }
}
