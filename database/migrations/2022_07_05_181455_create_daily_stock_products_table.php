<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDailyStockProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('daily_stock_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('daily_stock_id')->nullable()->index();
            $table->unsignedBigInteger('product_id')->nullable()->index();
            $table->unsignedBigInteger('variant_id')->nullable()->index();
            $table->decimal('quantity', 22)->default(0);
            $table->string('unit', 50)->nullable();
            $table->decimal('unit_cost_exc_tax', 22)->default(0);
            $table->decimal('tax_percent', 22)->default(0);
            $table->unsignedBigInteger('tax_id')->nullable()->index();
            $table->tinyInteger('tax_type')->default(1);
            $table->decimal('tax_amount', 22)->default(0);
            $table->decimal('unit_cost_inc_tax', 22)->default(0);
            $table->decimal('subtotal', 22)->default(0);
            $table->boolean('is_delete_in_update')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('daily_stock_products');
    }
}
