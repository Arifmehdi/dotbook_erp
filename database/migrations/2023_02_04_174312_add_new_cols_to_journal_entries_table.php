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
        Schema::table('journal_entries', function (Blueprint $table) {

            $table->unsignedBigInteger('user_id')->after('account_id')->nullable();
            $table->unsignedBigInteger('payment_method_id')->after('user_id')->nullable();
            $table->string('transaction_no')->after('payment_method_id')->nullable();
            $table->string('cheque_no')->after('transaction_no')->nullable();
            $table->string('cheque_serial_no')->after('cheque_no')->nullable();
            $table->string('cheque_issue_date')->after('cheque_serial_no')->nullable();
            $table->string('remarkable_note')->after('cheque_issue_date')->nullable();
            $table->string('amount_type')->after('remarkable_note')->comment('dr/cr');

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('payment_method_id')->references('id')->on('payment_methods')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('journal_entries', function (Blueprint $table) {

            $table->dropForeign(['user_id']);
            $table->dropForeign(['payment_method_id']);
            $table->dropColumn('user_id');
            $table->dropColumn('payment_method_id');
            $table->dropColumn('transaction_no');
            $table->dropColumn('cheque_no');
            $table->dropColumn('cheque_serial_no');
            $table->dropColumn('cheque_issue_date');
            $table->dropColumn('remarkable_note');
            $table->dropColumn('amount_type');
        });
    }
};
