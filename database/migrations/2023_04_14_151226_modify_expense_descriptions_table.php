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
        Schema::table('expense_descriptions', function (Blueprint $table) {

            $table->unsignedBigInteger('account_id')->after('expense_account_id')->nullable();
            $table->unsignedBigInteger('payment_method_id')->after('account_id')->nullable();
            $table->string('transaction_no', 255)->after('payment_method_id')->nullable();
            $table->string('cheque_no', 255)->after('transaction_no')->nullable();
            $table->string('cheque_serial_no', 255)->after('cheque_no')->nullable();
            $table->string('cheque_issue_date', 255)->after('cheque_serial_no')->nullable();
            $table->string('amount_type', 255)->after('cheque_issue_date')->nullable();

            $table->foreign('account_id')->references('id')->on('accounts')->onDelete('cascade');
            $table->foreign('payment_method_id')->references('id')->on('payment_methods')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('expense_descriptions', function (Blueprint $table) {
            $table->dropForeign(['account_id']);
            $table->dropColumn('account_id');
            $table->dropForeign(['payment_method_id']);
            $table->dropColumn('payment_method_id');
            $table->dropColumn('transaction_no');
            $table->dropColumn('cheque_no');
            $table->dropColumn('cheque_serial_no');
            $table->dropColumn('cheque_issue_date');
            $table->dropColumn('amount_type');
        });
    }
};
