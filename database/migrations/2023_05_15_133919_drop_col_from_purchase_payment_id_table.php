<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('account_ledgers', function (Blueprint $table) {
            $table->dropForeign(['loan_additional_expense_id']);
            $table->dropColumn('loan_additional_expense_id');
            $table->dropForeign(['loan_payment_additional_expense_id']);
            $table->dropColumn('loan_payment_additional_expense_id');
            $table->dropForeign(['expense_payment_id']);
            $table->dropColumn('expense_payment_id');
            $table->dropForeign(['sale_payment_id']);
            $table->dropColumn('sale_payment_id');
            $table->dropForeign(['supplier_payment_id']);
            $table->dropColumn('supplier_payment_id');
            $table->dropForeign(['customer_payment_id']);
            $table->dropColumn('customer_payment_id');
            $table->dropForeign(['loan_payment_id']);
            $table->dropColumn('loan_payment_id');
            $table->dropForeign(['contra_credit_id']);
            $table->dropColumn('contra_credit_id');
            $table->dropForeign(['contra_debit_id']);
            $table->dropColumn('contra_debit_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('account_ledgers', function (Blueprint $table) {
            //
        });
    }
};
