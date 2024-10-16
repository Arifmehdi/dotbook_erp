<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToWarehouseBranchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('warehouse_branches', function (Blueprint $table) {
            $table->foreign(['branch_id'])->references(['id'])->on('branches')->onDelete('CASCADE');
            $table->foreign(['warehouse_id'])->references(['id'])->on('warehouses')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('warehouse_branches', function (Blueprint $table) {
            $table->dropForeign('warehouse_branches_branch_id_foreign');
            $table->dropForeign('warehouse_branches_warehouse_id_foreign');
        });
    }
}
