<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransferStockToWarehousesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transfer_stock_to_warehouses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('invoice_id');
            $table->tinyInteger('status')->default(1)->comment('1=pending;2=partial;3=completed');
            $table->unsignedBigInteger('branch_id')->nullable()->index()->comment('form_branch');
            $table->unsignedBigInteger('warehouse_id')->index()->comment('to_warehouse');
            $table->decimal('total_item');
            $table->decimal('total_send_qty', 22)->default(0);
            $table->decimal('total_received_qty', 22)->default(0);
            $table->decimal('net_total_amount', 22)->default(0);
            $table->decimal('shipping_charge', 22)->default(0);
            $table->mediumText('additional_note')->nullable();
            $table->mediumText('receiver_note')->nullable();
            $table->string('date')->nullable();
            $table->string('month')->nullable();
            $table->string('year')->nullable();
            $table->unsignedBigInteger('admin_id')->nullable();
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
        Schema::dropIfExists('transfer_stock_to_warehouses');
    }
}
