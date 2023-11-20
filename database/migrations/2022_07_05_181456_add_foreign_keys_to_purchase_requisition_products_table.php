<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToPurchaseRequisitionProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchase_requisition_products', function (Blueprint $table) {
            $table->foreign(['product_id'])->references(['id'])->on('products')->onDelete('CASCADE');
            $table->foreign(['variant_id'])->references(['id'])->on('product_variants')->onDelete('CASCADE');
            $table->foreign(['requisition_id'])->references(['id'])->on('purchase_requisitions')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchase_requisition_products', function (Blueprint $table) {
            $table->dropForeign('purchase_requisition_products_product_id_foreign');
            $table->dropForeign('purchase_requisition_products_variant_id_foreign');
            $table->dropForeign('purchase_requisition_products_requisition_id_foreign');
        });
    }
}
