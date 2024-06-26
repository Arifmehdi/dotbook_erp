<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMoneyReceiptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('money_receipts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('invoice_id', 191)->nullable();
            $table->decimal('amount', 22)->nullable();
            $table->unsignedBigInteger('customer_id')->index();
            $table->boolean('is_customer_name')->default(false);
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->mediumText('note')->nullable();
            $table->string('receiver')->nullable();
            $table->string('ac_details')->nullable();
            $table->boolean('is_date')->default(false);
            $table->boolean('is_header_less')->default(false);
            $table->bigInteger('gap_from_top')->nullable();
            $table->string('date')->nullable();
            $table->string('month')->nullable();
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
        Schema::dropIfExists('money_receipts');
    }
}
