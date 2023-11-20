<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('import_products', function (Blueprint $table) {

            $table->id();
            $table->unsignedBigInteger('import_id')->nullable();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->unsignedBigInteger('variant_id')->nullable();
            $table->decimal('quantity', 22, 2)->default(0);
            $table->string('unit')->nullable();
            $table->decimal('unit_cost_exc_tax', 22, 2)->default(0);
            $table->decimal('discount', 22, 2)->default(0);
            $table->tinyInteger('discount_type')->default(0)->comment('1=Fixed,2=percentage');
            $table->decimal('discount_amount', 22, 2)->default(0);
            $table->decimal('unit_cost_with_discount', 22, 2)->default(0);
            $table->decimal('impo_subtotal', 22, 2)->default(0);
            $table->unsignedBigInteger('currency_id')->nullable();
            $table->decimal('currency_rate', 22, 2)->default(0);
            $table->decimal('local_currency_unit_cost_exc_tax', 22, 2)->default(0);
            $table->decimal('custom', 22, 2)->default(0);
            $table->tinyInteger('custom_type')->default(1)->comment('1=Fixed,2=percentage');
            $table->decimal('custom_amount', 22, 2)->default(0);
            $table->decimal('duty', 22, 2)->default(0);
            $table->tinyInteger('duty_type')->default(1)->comment('1=Fixed,2=percentage');
            $table->decimal('duty_amount', 22, 2)->default(0);
            $table->decimal('vat', 22, 2)->default(0);
            $table->tinyInteger('vat_type')->default(1)->comment('1=Fixed,2=percentage');
            $table->decimal('vat_amount', 22, 2)->default(0);
            $table->decimal('at', 22, 2)->default(0);
            $table->tinyInteger('at_type')->default(1)->comment('1=Fixed,2=percentage');
            $table->decimal('at_amount', 22, 2)->default(0);
            $table->decimal('ait', 22, 2)->default(0);
            $table->tinyInteger('ait_type')->default(1)->comment('1=Fixed,2=percentage');
            $table->decimal('ait_amount', 22, 2)->default(0);
            $table->decimal('total_additional_cost', 22, 2)->default(0);
            $table->decimal('net_unit_cost', 22, 2)->default(0);
            $table->decimal('local_subtotal', 22, 2)->default(0);
            $table->boolean('is_delete_in_update')->default(0);
            $table->timestamps();
            $table->foreign('import_id')->references('id')->on('imports')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('variant_id')->references('id')->on('product_variants')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('import_products');
    }
};
