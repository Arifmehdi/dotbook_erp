<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('productions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('unit_id')->nullable()->index();
            $table->unsignedBigInteger('tax_id')->nullable()->index();
            $table->tinyInteger('tax_type')->nullable();
            $table->string('reference_no')->nullable();
            $table->string('date')->nullable();
            $table->string('time', 20)->nullable();
            $table->timestamp('report_date')->nullable();
            $table->unsignedBigInteger('warehouse_id')->nullable()->index();
            $table->unsignedBigInteger('stock_warehouse_id')->nullable()->index();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->unsignedBigInteger('stock_branch_id')->nullable()->index();
            $table->unsignedBigInteger('product_id')->nullable()->index();
            $table->unsignedBigInteger('variant_id')->nullable()->index();
            $table->decimal('total_ingredient_cost', 22)->nullable();
            $table->decimal('quantity', 22)->nullable();
            $table->decimal('parameter_quantity', 22)->default(0);
            $table->decimal('wasted_quantity', 22)->nullable();
            $table->decimal('total_final_quantity', 22)->default(0);
            $table->decimal('unit_cost_exc_tax', 22)->default(0);
            $table->decimal('unit_cost_inc_tax', 22)->default(0);
            $table->decimal('x_margin', 22)->default(0);
            $table->decimal('price_exc_tax', 22)->default(0);
            $table->decimal('production_cost', 22)->nullable();
            $table->decimal('total_cost', 22)->nullable();
            $table->boolean('is_final')->default(false);
            $table->boolean('is_last_entry')->default(false);
            $table->boolean('is_default_price')->default(false);
            $table->unsignedBigInteger('production_account_id')->nullable()->index();
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
        Schema::dropIfExists('productions');
    }
}
