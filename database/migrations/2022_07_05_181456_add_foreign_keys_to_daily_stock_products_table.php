<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToDailyStockProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('daily_stock_products', function (Blueprint $table) {
            $table->foreign(['variant_id'])->references(['id'])->on('product_variants')->onDelete('CASCADE');
            $table->foreign(['daily_stock_id'])->references(['id'])->on('daily_stocks')->onDelete('CASCADE');
            $table->foreign(['tax_id'])->references(['id'])->on('taxes')->onDelete('SET NULL');
            $table->foreign(['product_id'])->references(['id'])->on('products')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('daily_stock_products', function (Blueprint $table) {
            $table->dropForeign('daily_stock_products_variant_id_foreign');
            $table->dropForeign('daily_stock_products_daily_stock_id_foreign');
            $table->dropForeign('daily_stock_products_tax_id_foreign');
            $table->dropForeign('daily_stock_products_product_id_foreign');
        });
    }
}
