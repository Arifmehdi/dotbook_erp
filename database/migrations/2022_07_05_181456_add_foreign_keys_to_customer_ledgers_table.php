<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToCustomerLedgersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customer_ledgers', function (Blueprint $table) {
            $table->foreign(['sale_id'])->references(['id'])->on('sales')->onDelete('CASCADE');
            $table->foreign(['sale_return_id'])->references(['id'])->on('sale_returns')->onDelete('CASCADE');
            $table->foreign(['customer_id'])->references(['id'])->on('customers')->onDelete('CASCADE');
            $table->foreign(['money_receipt_id'])->references(['id'])->on('money_receipts')->onDelete('CASCADE');
            $table->foreign(['sale_payment_id'])->references(['id'])->on('sale_payments')->onDelete('CASCADE');
            $table->foreign(['customer_payment_id'])->references(['id'])->on('customer_payments')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customer_ledgers', function (Blueprint $table) {
            $table->dropForeign('customer_ledgers_sale_id_foreign');
            $table->dropForeign('customer_ledgers_sale_return_id_foreign');
            $table->dropForeign('customer_ledgers_customer_id_foreign');
            $table->dropForeign('customer_ledgers_money_receipt_id_foreign');
            $table->dropForeign('customer_ledgers_sale_payment_id_foreign');
            $table->dropForeign('customer_ledgers_customer_payment_id_foreign');
        });
    }
}
