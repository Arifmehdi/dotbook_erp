<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToSupplierPaymentInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('supplier_payment_invoices', function (Blueprint $table) {
            $table->foreign(['purchase_id'])->references(['id'])->on('purchases')->onDelete('CASCADE');
            $table->foreign(['supplier_return_id'])->references(['id'])->on('purchase_returns')->onDelete('CASCADE');
            $table->foreign(['supplier_payment_id'])->references(['id'])->on('supplier_payments')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('supplier_payment_invoices', function (Blueprint $table) {
            $table->dropForeign('supplier_payment_invoices_purchase_id_foreign');
            $table->dropForeign('supplier_payment_invoices_supplier_return_id_foreign');
            $table->dropForeign('supplier_payment_invoices_supplier_payment_id_foreign');
        });
    }
}
