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
        Schema::table('receive_stocks', function (Blueprint $table) {

            $table->decimal('net_weight', 22, 2)->after('status')->nullable();
            $table->string('vehicle_no')->after('net_weight')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('receive_stocks', function (Blueprint $table) {

            $table->dropColumn('net_weight');
            $table->dropColumn('vehicle_no');
        });
    }
};
