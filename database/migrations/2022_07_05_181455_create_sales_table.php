<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('invoice_id', 100)->nullable();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->unsignedBigInteger('customer_id')->nullable()->index();
            $table->unsignedBigInteger('sale_account_id')->nullable()->index();
            $table->bigInteger('total_item');
            $table->decimal('net_total_amount', 22)->default(0);
            $table->tinyInteger('order_discount_type')->default(1);
            $table->decimal('order_discount', 22)->default(0);
            $table->decimal('order_discount_amount', 22)->default(0);
            $table->decimal('redeem_point', 22)->default(0);
            $table->decimal('redeem_point_rate', 22)->default(0);
            $table->string('shipment_details')->nullable();
            $table->mediumText('shipment_address')->nullable();
            $table->decimal('shipment_charge', 22)->default(0);
            $table->tinyInteger('shipment_status')->nullable();
            $table->mediumText('delivered_to')->nullable();
            $table->mediumText('sale_note')->nullable();
            $table->decimal('order_tax_percent', 22)->default(0);
            $table->decimal('order_tax_amount', 22)->default(0);
            $table->decimal('total_payable_amount', 22)->default(0);
            $table->decimal('paid', 22)->default(0);
            $table->decimal('change_amount', 22)->default(0);
            $table->decimal('due', 22)->default(0);
            $table->boolean('is_return_available')->default(false);
            $table->boolean('ex_status')->default(false)->comment('0=exchangeed,1=exchanged');
            $table->decimal('sale_return_amount', 22)->default(0);
            $table->decimal('sale_return_due', 22)->default(0);
            $table->mediumText('payment_note')->nullable();
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->tinyInteger('status')->default(1)->comment('1=final;2=draft;3=order;4=quatation;5=hold;6=suspended;7=DO');
            $table->boolean('quotation_status')->default(false);
            $table->boolean('order_status')->default(false);
            $table->boolean('do_status')->default(false);
            $table->unsignedBigInteger('do_approved_by_id')->nullable()->index();
            $table->unsignedBigInteger('sale_by_id')->nullable()->index();
            $table->unsignedBigInteger('quotation_by_id')->nullable()->index();
            $table->unsignedBigInteger('order_by_id')->nullable()->index();
            $table->unsignedBigInteger('do_by_id')->nullable()->index();
            $table->unsignedBigInteger('do_to_final_by_id')->nullable()->index();
            $table->string('date', 191)->nullable();
            $table->string('time', 191)->nullable();
            $table->timestamp('report_date')->nullable();
            $table->timestamp('expire_date')->nullable();
            $table->boolean('do_approval')->default(false);
            $table->string('do_car_number', 100)->nullable();
            $table->decimal('do_car_weight', 22)->default(0);
            $table->string('do_driver_name', 100)->nullable();
            $table->string('do_driver_phone', 100)->nullable();
            $table->decimal('do_car_last_weight', 22)->default(0);
            $table->string('attachment', 191)->nullable();
            $table->decimal('gross_pay', 22)->default(0);
            $table->decimal('previous_due', 22)->default(0);
            $table->decimal('all_total_payable', 22)->default(0);
            $table->decimal('previous_due_paid', 22)->default(0);
            $table->decimal('customer_running_balance', 22)->default(0);
            $table->tinyInteger('created_by')->default(1)->comment('1=add_sale;2=pos');
            $table->timestamps();
            $table->timestamp('quotation_date')->nullable();
            $table->timestamp('order_date')->nullable();
            $table->timestamp('do_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales');
    }
}
