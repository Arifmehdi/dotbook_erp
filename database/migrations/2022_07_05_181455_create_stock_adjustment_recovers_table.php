<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockAdjustmentRecoversTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_adjustment_recovers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('voucher_no', 191)->nullable();
            $table->unsignedBigInteger('stock_adjustment_id')->nullable()->index();
            $table->unsignedBigInteger('account_id')->nullable()->index();
            $table->unsignedBigInteger('payment_method_id')->nullable()->index();
            $table->decimal('recovered_amount', 22)->default(0);
            $table->mediumText('note')->nullable();
            $table->timestamp('report_date')->nullable();
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
        Schema::dropIfExists('stock_adjustment_recovers');
    }
}
