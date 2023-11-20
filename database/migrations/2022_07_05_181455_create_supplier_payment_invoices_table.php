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
        Schema::create('supplier_payment_invoices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('supplier_payment_id')->nullable()->index();
            $table->unsignedBigInteger('purchase_id')->nullable()->index();
            $table->unsignedBigInteger('supplier_return_id')->nullable()->index();
            $table->decimal('paid_amount', 22)->default(0);
            $table->timestamps();
            $table->tinyInteger('type')->default(1)->comment('1=purchase_due;2=purchase_return_due');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('supplier_payment_invoices');
    }
};
