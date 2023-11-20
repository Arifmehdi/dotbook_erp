<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransferStockBranchToBranchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transfer_stock_branch_to_branches', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('ref_id', 191)->nullable();
            $table->unsignedBigInteger('sender_branch_id')->nullable()->index();
            $table->unsignedBigInteger('sender_warehouse_id')->nullable()->index();
            $table->unsignedBigInteger('receiver_branch_id')->nullable()->index();
            $table->unsignedBigInteger('receiver_warehouse_id')->nullable()->index();
            $table->decimal('total_item', 22)->default(0);
            $table->decimal('total_stock_value', 22)->default(0);
            $table->unsignedBigInteger('expense_account_id')->nullable()->index();
            $table->unsignedBigInteger('bank_account_id')->nullable()->index();
            $table->unsignedBigInteger('payment_method_id')->nullable()->index();
            $table->string('payment_note')->nullable();
            $table->decimal('transfer_cost', 22)->default(0);
            $table->decimal('total_send_qty', 22)->default(0);
            $table->decimal('total_received_qty', 22)->default(0);
            $table->decimal('total_pending_qty', 22)->default(0);
            $table->tinyInteger('receive_status')->default(1);
            $table->string('date')->nullable();
            $table->mediumText('transfer_note')->nullable();
            $table->mediumText('receiver_note')->nullable();
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
        Schema::dropIfExists('transfer_stock_branch_to_branches');
    }
}
