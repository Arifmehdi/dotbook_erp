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
            if (Schema::hasColumn('weight_scales', 'weight')) {
                $table->renameColumn('weight', 'do_car_last_weight');
            }
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
