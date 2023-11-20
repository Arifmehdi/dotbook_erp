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
        Schema::table('loan_payments', function (Blueprint $table) {
            $table->dropForeign('loan_payments_company_id_foreign');
            $table->dropForeign('loan_payments_user_id_foreign');

            $table->dropColumn('company_id');
            $table->dropColumn('pay_mode');
            $table->dropColumn('user_id');
            $table->tinyInteger('payment_type')->change()->comment('1=Loan Installment Receipt, 2= Loan Installment Payment');
            $table->renameColumn('paid_amount', 'amount');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('loan_payments', function (Blueprint $table) {
            //
        });
    }
};
