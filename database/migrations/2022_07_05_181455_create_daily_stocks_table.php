<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDailyStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('daily_stocks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('voucher_no')->nullable();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->unsignedBigInteger('warehouse_id')->nullable()->index();
            $table->decimal('total_item', 22)->default(1);
            $table->decimal('total_qty', 22)->default(1);
            $table->decimal('total_stock_value', 22)->default(1);
            $table->string('date', 20)->nullable();
            $table->timestamp('report_date')->nullable();
            $table->string('reported_by')->nullable();
            $table->unsignedBigInteger('created_by_id')->nullable()->index();
            $table->mediumText('production_details')->nullable();
            $table->text('note')->nullable();
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
        Schema::dropIfExists('daily_stocks');
    }
}
