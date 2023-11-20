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

            $table->unsignedBigInteger('loan_payment_additional_expense_id')->after('loan_additional_expense_id')->nullable();
            $table->foreign('loan_payment_additional_expense_id')->references('id')->on('loan_payment_additional_expenses')->onDelete('cascade');
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

            $table->dropForeign('account_ledgers_loan_payment_additional_expense_id_foreign');
            $table->dropColumn('loan_payment_additional_expense_id');
        });
    }
};
