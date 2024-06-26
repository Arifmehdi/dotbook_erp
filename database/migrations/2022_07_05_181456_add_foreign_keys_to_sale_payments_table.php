<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToSalePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sale_payments', function (Blueprint $table) {
            $table->foreign(['customer_payment_id'])->references(['id'])->on('customer_payments')->onDelete('CASCADE');
            $table->foreign(['sale_id'])->references(['id'])->on('sales')->onDelete('CASCADE');
            $table->foreign(['account_id'])->references(['id'])->on('accounts')->onDelete('CASCADE');
            $table->foreign(['customer_id'])->references(['id'])->on('customers')->onDelete('CASCADE');
            $table->foreign(['payment_method_id'])->references(['id'])->on('payment_methods')->onDelete('SET NULL');
            $table->foreign(['sale_return_id'])->references(['id'])->on('sale_returns')->onDelete('CASCADE');
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
        Schema::table('sale_payments', function (Blueprint $table) {
            $table->dropForeign('sale_payments_customer_payment_id_foreign');
            $table->dropForeign('sale_payments_sale_id_foreign');
            $table->dropForeign('sale_payments_account_id_foreign');
            $table->dropForeign('sale_payments_customer_id_foreign');
            $table->dropForeign('sale_payments_payment_method_id_foreign');
            $table->dropForeign('sale_payments_sale_return_id_foreign');
            $table->dropForeign('sale_payments_branch_id_foreign');
        });
    }
}
