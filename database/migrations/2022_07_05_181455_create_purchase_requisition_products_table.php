<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseRequisitionProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_requisition_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('requisition_id')->nullable()->index();
            $table->unsignedBigInteger('product_id')->nullable()->index();
            $table->unsignedBigInteger('variant_id')->nullable()->index();
            $table->decimal('last_purchase_price', 22)->default(0);
            $table->decimal('current_stock', 22)->default(0);
            $table->decimal('quantity', 22)->default(0);
            $table->string('unit', 100)->nullable();
            $table->text('purpose')->nullable();
            $table->tinyInteger('pr_type')->default(1)->comment('1=Normal,2=Emergency');
            $table->boolean('is_delete_in_update')->default(false);
            $table->timestamps();
            $table->tinyInteger('order_status')->default(0)->comment('0=Pending,1=ordered');
            $table->tinyInteger('purchase_status')->default(0)->comment('0=Pending,1=purchased');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchase_requisition_products');
    }
}
