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

            $table->decimal('total_sold_qty')->after('total_item')->default(0)->comment('only_for_direct_final_sale');
            $table->decimal('total_ordered_qty')->after('total_sold_qty')->default(0)->comment('only_for_sales_order');
            $table->decimal('total_delivered_qty')->after('total_ordered_qty')->default(0)->comment('only_for_order/delivery_order');
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
