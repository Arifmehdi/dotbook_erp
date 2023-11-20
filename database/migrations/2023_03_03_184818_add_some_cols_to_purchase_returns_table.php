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
        Schema::table('purchase_returns', function (Blueprint $table) {
            $table->string('voucher_no', 255)->after('id')->nullable();
            $table->unsignedBigInteger('supplier_account_id')->after('voucher_no')->nullable();
            $table->decimal('net_total_amount', 22, 2)->after('branch_id')->default(0);
            $table->decimal('return_discount', 22, 2)->after('net_total_amount')->default(0);
            $table->tinyInteger('return_discount_type')->after('return_discount')->default(1);
            $table->decimal('return_discount_amount', 22, 2)->after('return_discount_type')->default(0);
            $table->unsignedBigInteger('tax_ac_id')->after('return_discount_amount')->nullable();
            $table->tinyInteger('tax_type')->after('tax_ac_id')->default(1);
            $table->decimal('return_tax_percent', 22, 2)->after('tax_type')->default(0);
            $table->decimal('return_tax_amount', 22, 2)->after('return_tax_percent')->default(0);
            $table->unsignedBigInteger('created_by_id')->after('total_return_amount')->nullable();
            $table->foreign('supplier_account_id')->references('id')->on('accounts')->onDelete('cascade');
            $table->foreign('tax_ac_id')->references('id')->on('accounts')->onDelete('set null');
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
        Schema::table('purchase_returns', function (Blueprint $table) {
            //
        });
    }
};
