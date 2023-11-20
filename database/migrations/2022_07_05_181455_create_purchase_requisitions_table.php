<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseRequisitionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_requisitions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('department_id')->nullable()->index();
            $table->unsignedBigInteger('requester_id')->nullable()->index();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->string('requisition_no', 200)->nullable();
            $table->decimal('total_item', 22)->default(0);
            $table->decimal('total_qty', 22)->default(0);
            $table->decimal('total_purchase_order', 22)->default(0);
            $table->decimal('total_purchase', 22)->default(0);
            $table->text('note')->nullable();
            $table->boolean('is_approved')->default(false);
            $table->string('date', 50)->nullable();
            $table->timestamp('report_date')->nullable();
            $table->unsignedBigInteger('created_by_id')->nullable()->index();
            $table->unsignedBigInteger('approved_by_id')->nullable()->index();
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
        Schema::dropIfExists('purchase_requisitions');
    }
}
