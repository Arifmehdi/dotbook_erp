<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToStockIssueProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stock_issue_products', function (Blueprint $table) {
            $table->foreign(['product_id'])->references(['id'])->on('products')->onDelete('CASCADE');
            $table->foreign(['variant_id'])->references(['id'])->on('product_variants')->onDelete('CASCADE');
            $table->foreign(['stock_issue_id'])->references(['id'])->on('stock_issues')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stock_issue_products', function (Blueprint $table) {
            $table->dropForeign('stock_issue_products_product_id_foreign');
            $table->dropForeign('stock_issue_products_variant_id_foreign');
            $table->dropForeign('stock_issue_products_stock_issue_id_foreign');
        });
    }
}
