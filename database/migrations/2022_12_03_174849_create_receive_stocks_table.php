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
        Schema::create('receive_stocks', function (Blueprint $table) {
            $table->id();
            $table->string('voucher_no')->nullable();
            $table->string('challan_no')->nullable();
            $table->string('challan_date')->nullable();
            $table->decimal('total_item', 22, 2)->default(0);
            $table->decimal('total_qty', 22, 2)->default(0);
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->unsignedBigInteger('warehouse_id')->nullable();
            $table->unsignedBigInteger('supplier_id')->nullable();
            $table->unsignedBigInteger('requisition_id')->nullable();
            $table->string('date')->nullable();
            $table->timestamp('date_ts')->nullable();
            $table->boolean('status')->default(0);
            $table->text('note')->nullable();
            $table->unsignedBigInteger('created_by_id')->nullable();
            $table->timestamps();

            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
            $table->foreign('warehouse_id')->references('id')->on('warehouses')->onDelete('cascade');
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('cascade');
            $table->foreign('requisition_id')->references('id')->on('purchase_requisitions')->onDelete('cascade');
            $table->foreign('created_by_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('receive_stocks');
    }
};
