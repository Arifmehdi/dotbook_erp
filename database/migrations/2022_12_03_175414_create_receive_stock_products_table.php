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
        Schema::create('receive_stock_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('receive_stock_id')->nullable();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->unsignedBigInteger('variant_id')->nullable();
            $table->decimal('quantity', 22, 2)->default(0);
            $table->string('unit')->nullable();
            $table->string('lot_number')->nullable();
            $table->text('short_description')->nullable();
            $table->boolean('is_delete_in_update')->default(0);
            $table->timestamps();

            $table->foreign('receive_stock_id')->references('id')->on('receive_stocks')->onDelete('cascade');
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
        Schema::dropIfExists('receive_stock_products');
    }
};
