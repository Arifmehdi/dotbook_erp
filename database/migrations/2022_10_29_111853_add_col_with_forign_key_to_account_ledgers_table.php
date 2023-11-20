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
        Schema::table('account_ledgers', function (Blueprint $table) {
            if (Schema::hasColumns('account_ledgers', ['income_id', 'income_receipt_id'])) {
                $table->foreign('income_id')->references('id')->on('incomes')->onDelete('cascade');
                $table->foreign('income_receipt_id')->references('id')->on('income_receipts')->onDelete('cascade');
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
        Schema::table('account_ledgers', function (Blueprint $table) {
            $table->dropForeign('account_ledgers_income_id_index');
            $table->dropForeign('account_ledgers_income_receipt_id_index');
        });
    }
};
