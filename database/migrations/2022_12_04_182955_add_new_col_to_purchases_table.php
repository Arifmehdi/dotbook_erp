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
        Schema::table('purchases', function (Blueprint $table) {

            $table->unsignedBigInteger('receive_stock_id')->after('requisition_id')->nullable();
            $table->foreign('receive_stock_id')->references('id')->on('receive_stocks')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchases', function (Blueprint $table) {

            $table->dropForeign('purchases_receive_stock_id_foreign');
            $table->dropColumn('receive_stock_id');
        });
    }
};
