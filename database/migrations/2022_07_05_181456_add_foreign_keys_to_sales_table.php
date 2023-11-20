<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->foreign(['sale_by_id'])->references(['id'])->on('users')->onDelete('SET NULL');
            $table->foreign(['do_by_id'])->references(['id'])->on('users')->onDelete('SET NULL');
            $table->foreign(['order_by_id'])->references(['id'])->on('users')->onDelete('SET NULL');
            $table->foreign(['branch_id'])->references(['id'])->on('branches')->onDelete('CASCADE');
            $table->foreign(['sale_account_id'])->references(['id'])->on('accounts')->onDelete('SET NULL');
            $table->foreign(['do_approved_by_id'])->references(['id'])->on('users')->onDelete('SET NULL');
            $table->foreign(['do_to_final_by_id'])->references(['id'])->on('users')->onDelete('SET NULL');
            $table->foreign(['quotation_by_id'])->references(['id'])->on('users')->onDelete('SET NULL');
            $table->foreign(['customer_id'])->references(['id'])->on('customers')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropForeign('sales_sale_by_id_foreign');
            $table->dropForeign('sales_do_by_id_foreign');
            $table->dropForeign('sales_order_by_id_foreign');
            $table->dropForeign('sales_branch_id_foreign');
            $table->dropForeign('sales_sale_account_id_foreign');
            $table->dropForeign('sales_do_approved_by_id_foreign');
            $table->dropForeign('sales_do_to_final_by_id_foreign');
            $table->dropForeign('sales_quotation_by_id_foreign');
            $table->dropForeign('sales_customer_id_foreign');
        });
    }
}
