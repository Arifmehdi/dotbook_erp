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
        Schema::table('payment_description_references', function (Blueprint $table) {
            $table->unsignedBigInteger('stock_adjustment_id')->after('purchase_id')->nullable();
            $table->foreign('stock_adjustment_id')->references('id')->on('stock_adjustments')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payment_description_references', function (Blueprint $table) {
            $table->dropForeign(['stock_adjustment_id']);
            $table->dropColumn('stock_adjustment_id');
        });
    }
};
