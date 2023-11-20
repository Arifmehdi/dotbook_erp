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
        Schema::create('loan_payment_additional_expenses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('loan_payment_id')->nullable();
            $table->unsignedBigInteger('expense_account_id')->nullable();
            $table->decimal('amount', 22, 2)->default(0);
            $table->boolean('is_delete_in_update')->default(0);
            $table->timestamps();

            $table->foreign('loan_payment_id')->references('id')->on('loan_payments')->onDelete('cascade');
            $table->foreign('expense_account_id')->references('id')->on('accounts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('loan_payment_additional_expenses');
    }
};
