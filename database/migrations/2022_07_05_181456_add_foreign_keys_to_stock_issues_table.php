<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToStockIssuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stock_issues', function (Blueprint $table) {
            $table->foreign(['stock_event_id'])->references(['id'])->on('stock_events')->onDelete('SET NULL');
            $table->foreign(['branch_id'])->references(['id'])->on('branches')->onDelete('CASCADE');
            $table->foreign(['department_id'])->references(['id'])->on('departments')->onDelete('CASCADE');
            $table->foreign(['warehouse_id'])->references(['id'])->on('warehouses')->onDelete('CASCADE');
            $table->foreign(['created_by_id'])->references(['id'])->on('users')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stock_issues', function (Blueprint $table) {
            $table->dropForeign('stock_issues_stock_event_id_foreign');
            $table->dropForeign('stock_issues_branch_id_foreign');
            $table->dropForeign('stock_issues_department_id_foreign');
            $table->dropForeign('stock_issues_warehouse_id_foreign');
            $table->dropForeign('stock_issues_created_by_id_foreign');
        });
    }
}
