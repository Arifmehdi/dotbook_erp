<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToPurchasePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchase_payments', function (Blueprint $table) {
            $table->foreign(['purchase_id'])->references(['id'])->on('purchases')->onDelete('CASCADE');
            $table->foreign(['supplier_payment_id'])->references(['id'])->on('supplier_payments')->onDelete('CASCADE');
            $table->foreign(['account_id'])->references(['id'])->on('accounts')->onDelete('CASCADE');
            $table->foreign(['payment_method_id'])->references(['id'])->on('payment_methods')->onDelete('SET NULL');
            $table->foreign(['supplier_id'])->references(['id'])->on('suppliers')->onDelete('CASCADE');
            $table->foreign(['supplier_return_id'])->references(['id'])->on('purchase_returns')->onDelete('CASCADE');
            $table->foreign(['branch_id'])->references(['id'])->on('branches')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchase_payments', function (Blueprint $table) {
            $table->dropForeign('purchase_payments_purchase_id_foreign');
            $table->dropForeign('purchase_payments_supplier_payment_id_foreign');
            $table->dropForeign('purchase_payments_account_id_foreign');
            $table->dropForeign('purchase_payments_payment_method_id_foreign');
            $table->dropForeign('purchase_payments_supplier_id_foreign');
            $table->dropForeign('purchase_payments_supplier_return_id_foreign');
            $table->dropForeign('purchase_payments_branch_id_foreign');
        });
    }
}
