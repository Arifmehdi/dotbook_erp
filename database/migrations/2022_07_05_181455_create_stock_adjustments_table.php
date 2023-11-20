<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockAdjustmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_adjustments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('stock_adjustment_account_id')->nullable()->index();
            $table->unsignedBigInteger('warehouse_id')->nullable()->index();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->string('invoice_id')->nullable();
            $table->bigInteger('total_item')->default(0);
            $table->decimal('total_qty', 22)->default(0);
            $table->decimal('net_total_amount', 22)->default(0);
            $table->decimal('recovered_amount', 22)->default(0);
            $table->tinyInteger('type')->default(0);
            $table->string('date')->nullable();
            $table->string('time', 50)->nullable();
            $table->string('month')->nullable();
            $table->string('year')->nullable();
            $table->string('reason')->nullable();
            $table->timestamp('report_date_ts')->nullable();
            $table->unsignedBigInteger('admin_id')->nullable()->index();
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
        Schema::dropIfExists('stock_adjustments');
    }
}
