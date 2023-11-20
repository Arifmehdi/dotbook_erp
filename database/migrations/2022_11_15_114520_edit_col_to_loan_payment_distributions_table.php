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
        Schema::table('loan_payment_distributions', function (Blueprint $table) {

            $table->tinyInteger('payment_type')->comment('1=Payment, 2=Receipt')->change()->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('loan_payment_distributions', function (Blueprint $table) {

            $table->tinyInteger('payment_type')->comment('1=pay_loan_payment;2=get_loan_payment')->change()->default(1);
        });
    }
};
