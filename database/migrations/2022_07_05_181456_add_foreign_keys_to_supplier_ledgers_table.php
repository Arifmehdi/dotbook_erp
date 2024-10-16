<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToSupplierLedgersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('supplier_ledgers', function (Blueprint $table) {
            $table->foreign(['supplier_id'])->references(['id'])->on('suppliers')->onDelete('CASCADE');
            $table->foreign(['purchase_id'])->references(['id'])->on('purchases')->onDelete('CASCADE');
            $table->foreign(['purchase_return_id'])->references(['id'])->on('purchase_returns')->onDelete('CASCADE');
            $table->foreign(['supplier_payment_id'])->references(['id'])->on('supplier_payments')->onDelete('CASCADE');
            $table->foreign(['purchase_payment_id'])->references(['id'])->on('purchase_payments')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('supplier_ledgers', function (Blueprint $table) {
            $table->dropForeign('supplier_ledgers_supplier_id_foreign');
            $table->dropForeign('supplier_ledgers_purchase_id_foreign');
            $table->dropForeign('supplier_ledgers_purchase_return_id_foreign');
            $table->dropForeign('supplier_ledgers_supplier_payment_id_foreign');
            $table->dropForeign('supplier_ledgers_purchase_payment_id_foreign');
        });
    }
}
