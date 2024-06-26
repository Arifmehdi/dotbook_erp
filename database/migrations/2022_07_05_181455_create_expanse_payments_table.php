<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpansePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expanse_payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('invoice_id')->nullable();
            $table->unsignedBigInteger('expanse_id')->index();
            $table->unsignedBigInteger('account_id')->nullable()->index();
            $table->string('pay_mode')->nullable();
            $table->decimal('paid_amount', 22)->default(0);
            $table->tinyInteger('payment_status')->nullable()->comment('1=due;2=partial;3=paid');
            $table->string('date');
            $table->string('month');
            $table->string('year');
            $table->string('card_no')->nullable();
            $table->string('card_holder')->nullable();
            $table->string('card_type')->nullable();
            $table->string('card_transaction_no')->nullable();
            $table->string('card_month')->nullable();
            $table->string('card_year')->nullable();
            $table->string('card_secure_code')->nullable();
            $table->string('account_no')->nullable();
            $table->string('cheque_no')->nullable();
            $table->string('transaction_no')->nullable();
            $table->string('attachment')->nullable();
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->mediumText('note')->nullable();
            $table->timestamp('report_date')->useCurrentOnUpdate()->useCurrent();
            $table->timestamps();
            $table->unsignedBigInteger('payment_method_id')->nullable()->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('expanse_payments');
    }
}
