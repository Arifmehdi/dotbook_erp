<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToTransferStockBranchToBranchProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transfer_stock_branch_to_branch_products', function (Blueprint $table) {
            $table->foreign(['product_id'])->references(['id'])->on('products')->onDelete('CASCADE');
            $table->foreign(['variant_id'])->references(['id'])->on('product_variants')->onDelete('CASCADE');
            $table->foreign(['transfer_id'])->references(['id'])->on('transfer_stock_branch_to_branches')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transfer_stock_branch_to_branch_products', function (Blueprint $table) {
            $table->dropForeign('transfer_stock_branch_to_branch_products_product_id_foreign');
            $table->dropForeign('transfer_stock_branch_to_branch_products_variant_id_foreign');
            $table->dropForeign('transfer_stock_branch_to_branch_products_transfer_id_foreign');
        });
    }
}
