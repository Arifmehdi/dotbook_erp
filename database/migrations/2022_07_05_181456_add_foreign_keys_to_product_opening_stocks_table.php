<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToProductOpeningStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_opening_stocks', function (Blueprint $table) {
            $table->foreign(['warehouse_id'])->references(['id'])->on('warehouses')->onDelete('CASCADE');
            $table->foreign(['branch_id'])->references(['id'])->on('branches')->onDelete('CASCADE');
            $table->foreign(['product_variant_id'])->references(['id'])->on('product_variants')->onDelete('CASCADE');
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
        Schema::table('product_opening_stocks', function (Blueprint $table) {
            $table->dropForeign('product_opening_stocks_warehouse_id_foreign');
            $table->dropForeign('product_opening_stocks_branch_id_foreign');
            $table->dropForeign('product_opening_stocks_product_variant_id_foreign');
            $table->dropForeign('product_opening_stocks_product_id_foreign');
        });
    }
}
