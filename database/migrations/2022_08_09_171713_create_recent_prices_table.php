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
        Schema::create('recent_prices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->unsignedBigInteger('variant_id')->nullable();
            $table->decimal('previous_price', 22, 2)->default(0);
            $table->decimal('x_margin', 22, 2)->default(0);
            $table->decimal('new_price', 22, 2)->default(0);
            $table->unsignedBigInteger('created_by_id')->nullable();

            $table->timestamp('start_time')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('end_time')->default(\DB::raw('CURRENT_TIMESTAMP'));

            $table->timestamps();

            $table->foreign('created_by_id')->references('id')->on('users')->onDelete('set null');
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
        Schema::dropIfExists('recent_prices');
    }
};
