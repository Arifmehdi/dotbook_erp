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
        Schema::table('stock_adjustments', function (Blueprint $table) {
            $table->dropForeign(['stock_adjustment_account_id']);
            $table->dropColumn('stock_adjustment_account_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stock_adjustments', function (Blueprint $table) {
            $table->unsignedBigInteger('stock_adjustment_account_id')->after('voucher_no')->nullable();
            $table->foreign('stock_adjustment_account_id')->references('id')->on('accounts')->onDelete('cascade');
        });
    }
};
