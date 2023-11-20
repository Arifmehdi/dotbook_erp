<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToStockAdjustmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stock_adjustments', function (Blueprint $table) {
            $table->foreign(['warehouse_id'])->references(['id'])->on('warehouses')->onDelete('CASCADE');
            $table->foreign(['admin_id'])->references(['id'])->on('users')->onDelete('SET NULL');
            $table->foreign(['stock_adjustment_account_id'])->references(['id'])->on('accounts')->onDelete('CASCADE');
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
        Schema::table('stock_adjustments', function (Blueprint $table) {
            $table->dropForeign('stock_adjustments_warehouse_id_foreign');
            $table->dropForeign('stock_adjustments_admin_id_foreign');
            $table->dropForeign('stock_adjustments_stock_adjustment_account_id_foreign');
            $table->dropForeign('stock_adjustments_branch_id_foreign');
        });
    }
}
