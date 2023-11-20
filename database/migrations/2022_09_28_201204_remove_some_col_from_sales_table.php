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
        Schema::disableForeignKeyConstraints();
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn('do_car_number');
            $table->dropColumn('do_car_weight');
            $table->dropColumn('do_driver_name');
            $table->dropColumn('do_driver_phone');
            $table->dropColumn('do_car_last_weight');
            $table->dropColumn('is_do_done');
        });

        Schema::enableForeignKeyConstraints();
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
