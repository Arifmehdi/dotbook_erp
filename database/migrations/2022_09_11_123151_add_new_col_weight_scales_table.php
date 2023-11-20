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

            $table->decimal('first_weight', 22, 2)->after('weight')->default(0);
            $table->decimal('second_weight', 22, 2)->after('first_weight')->default(0);
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
