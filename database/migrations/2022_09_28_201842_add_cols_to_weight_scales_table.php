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
        Schema::table('weight_scales', function (Blueprint $table) {

            $table->unsignedBigInteger('delivery_order_id')->after('id')->nullable();
            $table->string('do_car_number')->after('delivery_order_id')->nullable();
            $table->string('do_driver_name')->after('do_car_number')->nullable();
            $table->string('do_driver_phone')->after('do_driver_name')->nullable();
            // $table->string('do_car_last_weight')->after('do_driver_phone')->nullable();
            $table->string('reserve_invoice_id')->after('do_car_last_weight')->nullable();
            $table->boolean('is_vehicle_done')->after('second_weight')->default(0);
            $table->foreign(['delivery_order_id'])->references(['id'])->on('sales')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('weight_scales', function (Blueprint $table) {
            //
        });
    }
};
