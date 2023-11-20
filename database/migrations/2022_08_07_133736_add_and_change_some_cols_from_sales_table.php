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
        Schema::table('sales', function (Blueprint $table) {
            $table->decimal('total_sold_qty', 22, 2)->change()->default(0);
            $table->decimal('total_ordered_qty', 22, 2)->change()->default(0);
            $table->decimal('total_delivered_qty', 22, 2)->change()->default(0);
            $table->decimal('total_do_qty', 22, 2)->after('total_ordered_qty')->default(0);
            $table->unsignedBigInteger('delivery_order_id')->after('total_do_qty')->nullable();
            $table->foreign('delivery_order_id')->references('id')->on('sales')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sales', function (Blueprint $table) {
            //
        });
    }
};
