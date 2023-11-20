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
            if (! Schema::hasColumns('account_ledgers', ['income_id', 'income_receipt_id'])) {
                $table->unsignedBigInteger('income_id')->after('expense_payment_id')->nullable();
                $table->unsignedBigInteger('income_receipt_id')->after('income_id')->nullable();
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
            $table->dropColumn('income_id');
            $table->dropColumn('income_receipt_id');
        });
    }
};
