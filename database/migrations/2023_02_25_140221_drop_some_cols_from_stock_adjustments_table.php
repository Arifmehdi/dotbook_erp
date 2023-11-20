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
            $table->dropForeign(['admin_id']);
            $table->dropColumn('admin_id');
            $table->dropColumn('invoice_id');
            $table->dropColumn('time');
            $table->dropColumn('month');
            $table->dropColumn('year');
            $table->dropColumn('report_date_ts');
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
            $table->unsignedBigInteger('admin_id')->after('report_date_ts')->nullable();
            $table->string('invoice_id', 255)->after('branch_id')->nullable();
            $table->string('time', 255)->after('date')->nullable();
            $table->string('month', 255)->after('time')->nullable();
            $table->string('year', 255)->after('month')->nullable();
            $table->timestamp('report_date_ts')->after('reason')->nullable();
            $table->foreign('admin_id')->references('id')->on('users')->onDelete('set null');
        });
    }
};
