<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProcessIngredientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('process_ingredients', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('process_id')->index();
            $table->unsignedBigInteger('product_id')->index();
            $table->unsignedBigInteger('variant_id')->nullable()->index();
            $table->decimal('wastage_percent', 22)->default(0);
            $table->decimal('wastage_amount', 22)->default(0);
            $table->decimal('final_qty', 22)->default(0);
            $table->unsignedBigInteger('unit_id')->nullable()->index();
            $table->decimal('unit_cost_inc_tax')->default(0);
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
        Schema::dropIfExists('process_ingredients');
    }
}
