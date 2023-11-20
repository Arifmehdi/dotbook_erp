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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->unsignedBigInteger('sale_ref_id')->nullable();
            $table->unsignedBigInteger('purchase_ref_id')->nullable();
            $table->string('voucher_no');
            $table->tinyInteger('payment_type')->comment('1=Receipt,2=Payment,3=IncomeReceipt,4=ExpensePayment');
            $table->string('date')->nullable();
            $table->timestamp('date_ts')->nullable();
            $table->unsignedBigInteger('created_by_id')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
            $table->foreign('created_by_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('sale_ref_id')->references('id')->on('sales')->onDelete('set null');
            $table->foreign('purchase_ref_id')->references('id')->on('purchases')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
};
