<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoanPaymentDistributionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan_payment_distributions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('loan_payment_id')->nullable()->index();
            $table->unsignedBigInteger('loan_id')->nullable()->index();
            $table->decimal('paid_amount', 22)->default(0);
            $table->tinyInteger('payment_type')->default(1)->comment('1=pay_loan_payment;2=get_loan_payment');
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
        Schema::dropIfExists('loan_payment_distributions');
    }
}
