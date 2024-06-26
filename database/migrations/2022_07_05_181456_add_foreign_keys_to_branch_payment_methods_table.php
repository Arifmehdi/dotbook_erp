<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToBranchPaymentMethodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('branch_payment_methods', function (Blueprint $table) {
            $table->foreign(['account_id'])->references(['id'])->on('accounts')->onDelete('SET NULL');
            $table->foreign(['payment_method_id'])->references(['id'])->on('payment_methods')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('branch_payment_methods', function (Blueprint $table) {
            $table->dropForeign('branch_payment_methods_account_id_foreign');
            $table->dropForeign('branch_payment_methods_payment_method_id_foreign');
        });
    }
}
