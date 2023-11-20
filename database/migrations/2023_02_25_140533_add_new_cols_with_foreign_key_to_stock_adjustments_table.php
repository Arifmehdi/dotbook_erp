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

            $table->string('voucher_no', 255)->after('id')->nullable();
            $table->timestamp('date_ts')->after('date')->nullable();
            $table->unsignedBigInteger('created_by_id')->after('date_ts')->nullable();
            $table->foreign('created_by_id')->references('id')->on('users')->onDelete('set null');
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

            $table->dropForeign(['created_by_id']);
            $table->dropColumn('created_by_id');
            $table->dropColumn('voucher_no');
        });
    }
};
