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
        Schema::create('contra_descriptions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('contra_id')->nullable();
            $table->unsignedBigInteger('account_id')->nullable();
            $table->unsignedBigInteger('payment_method_id')->nullable();
            $table->string('transaction_no')->nullable();
            $table->string('cheque_no')->nullable();
            $table->string('cheque_serial_no')->nullable();
            $table->string('cheque_issue_date')->nullable();
            $table->string('amount_type')->nullable()->comment('dr/cr');
            $table->decimal('amount', 22, 2)->default(0);
            $table->timestamps();

            $table->foreign('contra_id')->references('id')->on('contras')->onDelete('cascade');
            $table->foreign('account_id')->references('id')->on('accounts')->onDelete('cascade');
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
        Schema::dropIfExists('contra_descriptions');
    }
};
