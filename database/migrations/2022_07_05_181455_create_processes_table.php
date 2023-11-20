<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProcessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('processes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->unsignedBigInteger('product_id')->index();
            $table->unsignedBigInteger('variant_id')->nullable()->index();
            $table->decimal('total_ingredient_cost', 22)->default(0);
            $table->decimal('wastage_percent')->default(0);
            $table->decimal('wastage_amount')->default(0);
            $table->decimal('total_output_qty', 22)->default(0);
            $table->unsignedBigInteger('unit_id')->nullable()->index();
            $table->decimal('production_cost', 22)->default(0);
            $table->decimal('total_cost', 22)->default(0);
            $table->text('process_instruction')->nullable();
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
        Schema::dropIfExists('processes');
    }
}
