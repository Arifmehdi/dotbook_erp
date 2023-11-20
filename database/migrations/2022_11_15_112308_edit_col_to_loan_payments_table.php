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
        Schema::table('loan_payments', function (Blueprint $table) {

            $table->tinyInteger('payment_type')->comment('1=Loan Installment Receipt, 2= Loan Installment Payment')->change()->default(1);
        });
    }
};
