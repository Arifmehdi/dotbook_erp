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
        Schema::create('purchase_by_scale_weights', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('purchase_by_scale_id');
            $table->unsignedBigInteger('product_id')->nullable();
            $table->unsignedBigInteger('variant_id')->nullable();
            $table->decimal('scale_weight', 22, 2)->default(0);
            $table->decimal('differ_weight', 22, 2)->default(0);
            $table->decimal('wast', 22, 2)->default(0);
            $table->decimal('net_weight', 22, 2)->default(0);
            $table->boolean('is_delete_in_update')->default(0);
            $table->boolean('is_first_weight')->default(0);
            $table->boolean('is_last_weight')->default(0);
            $table->timestamps();
            $table->foreign(['purchase_by_scale_id'])->references(['id'])->on('purchase_by_scales')->onDelete('cascade');
            $table->foreign(['product_id'])->references(['id'])->on('products')->onDelete('cascade');
            $table->foreign(['variant_id'])->references(['id'])->on('product_variants')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchase_by_scale_weights');
    }
};
