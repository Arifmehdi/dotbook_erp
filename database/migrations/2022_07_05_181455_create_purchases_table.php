<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('invoice_id');
            $table->unsignedBigInteger('warehouse_id')->nullable()->index();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->unsignedBigInteger('supplier_id')->nullable()->index();
            $table->bigInteger('total_item');
            $table->decimal('net_total_amount', 22)->default(0);
            $table->decimal('order_discount', 22)->default(0);
            $table->tinyInteger('order_discount_type')->default(1);
            $table->decimal('order_discount_amount', 22)->default(0);
            $table->string('shipment_details')->nullable();
            $table->decimal('shipment_charge', 22)->default(0);
            $table->mediumText('purchase_note')->nullable();
            $table->unsignedBigInteger('purchase_tax_id')->nullable();
            $table->decimal('purchase_tax_percent', 22)->default(0);
            $table->decimal('purchase_tax_amount', 22)->default(0);
            $table->decimal('total_purchase_amount', 22)->default(0);
            $table->decimal('paid', 22)->default(0);
            $table->decimal('due', 22)->default(0);
            $table->decimal('purchase_return_amount', 22)->default(0);
            $table->decimal('purchase_return_due', 22)->default(0);
            $table->mediumText('payment_note')->nullable();
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->tinyInteger('purchase_status')->default(1);
            $table->boolean('is_purchased')->default(true);
            $table->string('date')->nullable();
            $table->string('delivery_date', 20)->nullable();
            $table->string('time', 191)->nullable();
            $table->timestamp('report_date')->nullable();
            $table->boolean('is_last_created')->default(false);
            $table->boolean('is_return_available')->default(false);
            $table->string('attachment')->nullable();
            $table->decimal('po_qty', 22)->default(0);
            $table->decimal('po_pending_qty', 22)->default(0);
            $table->decimal('po_received_qty', 22)->default(0);
            $table->string('po_receiving_status', 20)->nullable()->comment('This field only for order, which numeric status = 3');
            $table->timestamps();
            $table->unsignedBigInteger('purchase_account_id')->nullable()->index();
            $table->unsignedBigInteger('requisition_id')->nullable()->index();
            $table->string('challan_no')->nullable();
            $table->string('challan_date')->nullable();
            $table->string('carrier')->nullable();
            $table->string('vehicle_no')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchases');
    }
}
