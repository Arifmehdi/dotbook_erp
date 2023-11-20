<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('branch_id')->nullable()->index()->comment('This column for track branch wise FIFO/LIFO method.');
            $table->unsignedBigInteger('purchase_id')->nullable()->index();
            $table->unsignedBigInteger('product_id')->index();
            $table->unsignedBigInteger('product_variant_id')->nullable()->index();
            $table->decimal('quantity', 22)->nullable()->default(0);
            $table->string('unit')->nullable();
            $table->decimal('unit_cost', 22)->default(0);
            $table->decimal('unit_discount', 22)->default(0);
            $table->decimal('unit_discount_amount', 22)->default(0);
            $table->tinyInteger('unit_discount_type')->default(1);
            $table->decimal('unit_cost_with_discount', 22)->default(0);
            $table->decimal('subtotal', 22)->default(0)->comment('Without_tax');
            $table->decimal('unit_tax_percent', 22)->default(0);
            $table->decimal('unit_tax')->default(0)->comment('unit_tax=unit_tax_amount');
            $table->tinyInteger('tax_type')->default(1);
            $table->unsignedBigInteger('tax_id')->nullable()->index();
            $table->decimal('net_unit_cost', 22)->default(0)->comment('With_tax');
            $table->decimal('line_total', 22)->default(0);
            $table->decimal('profit_margin', 22)->default(0);
            $table->decimal('selling_price', 22)->default(0);
            $table->mediumText('description')->nullable();
            $table->boolean('is_received')->default(false);
            $table->string('lot_no', 191)->nullable();
            $table->boolean('delete_in_update')->default(false);
            $table->unsignedBigInteger('product_order_product_id')->nullable()->index()->comment('when product add from purchase_order_products table');
            $table->timestamps();
            $table->decimal('left_qty', 22)->default(0);
            $table->unsignedBigInteger('production_id')->nullable()->index();
            $table->unsignedBigInteger('opening_stock_id')->nullable()->index();
            $table->unsignedBigInteger('sale_return_product_id')->nullable()->index();
            $table->unsignedBigInteger('transfer_branch_to_branch_product_id')->nullable()->index();
            $table->unsignedBigInteger('daily_stock_product_id')->nullable()->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchase_products');
    }
}
