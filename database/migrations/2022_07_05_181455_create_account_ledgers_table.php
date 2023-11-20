<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountLedgersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_ledgers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->timestamp('date')->nullable();
            $table->string('voucher_type', 50)->nullable();
            $table->unsignedBigInteger('account_id')->nullable()->index();
            $table->unsignedBigInteger('expense_id')->nullable()->index();
            $table->unsignedBigInteger('expense_payment_id')->nullable()->index();
            $table->unsignedBigInteger('sale_id')->nullable()->index();
            $table->unsignedBigInteger('sale_payment_id')->nullable()->index();
            $table->unsignedBigInteger('supplier_payment_id')->nullable()->index();
            $table->unsignedBigInteger('sale_return_id')->nullable()->index();
            $table->unsignedBigInteger('purchase_id')->nullable()->index();
            $table->unsignedBigInteger('purchase_payment_id')->nullable()->index();
            $table->unsignedBigInteger('customer_payment_id')->nullable()->index();
            $table->unsignedBigInteger('purchase_return_id')->nullable()->index();
            $table->unsignedBigInteger('adjustment_id')->nullable()->index();
            $table->unsignedBigInteger('stock_adjustment_recover_id')->nullable()->index();
            $table->unsignedBigInteger('payroll_id')->nullable()->index();
            $table->unsignedBigInteger('payroll_payment_id')->nullable()->index();
            $table->unsignedBigInteger('production_id')->nullable()->index();
            $table->unsignedBigInteger('loan_id')->nullable()->index();
            $table->unsignedBigInteger('loan_payment_id')->nullable()->index();
            $table->unsignedBigInteger('contra_credit_id')->nullable()->index();
            $table->unsignedBigInteger('contra_debit_id')->nullable()->index();
            $table->decimal('debit', 22)->default(0);
            $table->decimal('credit', 22)->default(0);
            $table->decimal('running_balance', 22)->default(0);
            $table->string('amount_type', 20)->nullable()->comment('debit/credit');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('account_ledgers');
    }
}
