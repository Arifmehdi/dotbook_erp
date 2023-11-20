<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToCustomerPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customer_payments', function (Blueprint $table) {
            $table->foreign(['customer_id'])->references(['id'])->on('customers')->onDelete('CASCADE');
            $table->foreign(['account_id'])->references(['id'])->on('accounts')->onDelete('CASCADE');
            $table->foreign(['branch_id'])->references(['id'])->on('branches')->onDelete('CASCADE');
            $table->foreign(['payment_method_id'])->references(['id'])->on('payment_methods')->onDelete('SET NULL');
            $table->foreign(['admin_id'])->references(['id'])->on('users')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customer_payments', function (Blueprint $table) {
            $table->dropForeign('customer_payments_customer_id_foreign');
            $table->dropForeign('customer_payments_account_id_foreign');
            $table->dropForeign('customer_payments_branch_id_foreign');
            $table->dropForeign('customer_payments_payment_method_id_foreign');
            $table->dropForeign('customer_payments_admin_id_foreign');
        });
    }
}
