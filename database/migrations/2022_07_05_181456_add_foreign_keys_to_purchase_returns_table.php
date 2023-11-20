<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToPurchaseReturnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchase_returns', function (Blueprint $table) {
            $table->foreign(['supplier_id'])->references(['id'])->on('suppliers')->onDelete('CASCADE');
            $table->foreign(['branch_id'])->references(['id'])->on('branches')->onDelete('CASCADE');
            $table->foreign(['purchase_return_account_id'])->references(['id'])->on('accounts')->onDelete('SET NULL');
            $table->foreign(['warehouse_id'])->references(['id'])->on('warehouses')->onDelete('CASCADE');
            $table->foreign(['purchase_id'])->references(['id'])->on('purchases')->onDelete('CASCADE');
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
            $table->dropForeign('purchase_returns_supplier_id_foreign');
            $table->dropForeign('purchase_returns_branch_id_foreign');
            $table->dropForeign('purchase_returns_purchase_return_account_id_foreign');
            $table->dropForeign('purchase_returns_warehouse_id_foreign');
            $table->dropForeign('purchase_returns_purchase_id_foreign');
        });
    }
}
