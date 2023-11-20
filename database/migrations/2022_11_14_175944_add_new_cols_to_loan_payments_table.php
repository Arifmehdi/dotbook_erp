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

            $table->unsignedBigInteger('loan_account_id')->after('branch_id')->nullable();
            $table->decimal('total_additional_expense', 22, 2)->after('account_id')->default(0);
            $table->string('attachment')->after('report_date')->nullable();
            $table->unsignedBigInteger('created_by_id')->after('attachment')->nullable();
            $table->decimal('grand_total')->after('amount')->nullable();
            $table->renameColumn('amount', 'pay_amount');

            $table->foreign('loan_account_id')->references('id')->on('accounts')->onDelete('cascade');
            $table->foreign('created_by_id')->references('id')->on('users')->onDelete('cascade');
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
            $table->dropForeign('loan_payments_loan_account_id_foreign');
            $table->dropForeign('loan_payments_created_by_id_foreign');

            $table->dropColumn('loan_account_id');
            $table->renameColumn('pay_amount', 'amount');
            $table->dropColumn('total_additional_expense');
            $table->dropColumn('attachment');
            $table->dropColumn('created_by_id');
        });
    }
};
