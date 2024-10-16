<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseReturnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_returns', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('invoice_id');
            $table->unsignedBigInteger('purchase_id')->nullable()->index();
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->unsignedBigInteger('warehouse_id')->nullable()->index();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->unsignedBigInteger('supplier_id')->nullable()->index();
            $table->tinyInteger('return_type')->nullable()->comment('1=purchase_invoice_return;2=supplier_purchase_return');
            $table->decimal('total_return_amount', 22)->default(0);
            $table->decimal('total_return_due', 22)->default(0);
            $table->decimal('total_return_due_received', 22)->default(0);
            $table->decimal('purchase_tax_percent', 22)->default(0);
            $table->decimal('purchase_tax_amount', 22)->default(0);
            $table->string('date')->nullable();
            $table->string('month')->nullable();
            $table->string('year')->nullable();
            $table->timestamp('report_date')->nullable();
            $table->timestamps();
            $table->unsignedBigInteger('purchase_return_account_id')->nullable()->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchase_returns');
    }
}
