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
        Schema::table('payments', function (Blueprint $table) {
            $table->unsignedBigInteger('stock_adjustment_ref_id')->after('purchase_ref_id')->nullable();
            $table->foreign('stock_adjustment_ref_id')->references('id')->on('stock_adjustments')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['stock_adjustment_ref_id']);
            $table->dropColumn('stock_adjustment_ref_id');
        });
    }
};
