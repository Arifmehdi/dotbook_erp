<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToPurchaseRequisitionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchase_requisitions', function (Blueprint $table) {
            $table->foreign(['department_id'])->references(['id'])->on('departments')->onDelete('SET NULL');
            $table->foreign(['approved_by_id'])->references(['id'])->on('users')->onDelete('CASCADE');
            $table->foreign(['created_by_id'])->references(['id'])->on('users')->onDelete('CASCADE');
            $table->foreign(['requester_id'])->references(['id'])->on('requesters')->onDelete('SET NULL');
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
        Schema::table('purchase_requisitions', function (Blueprint $table) {
            $table->dropForeign('purchase_requisitions_department_id_foreign');
            $table->dropForeign('purchase_requisitions_approved_by_id_foreign');
            $table->dropForeign('purchase_requisitions_created_by_id_foreign');
            $table->dropForeign('purchase_requisitions_requester_id_foreign');
            $table->dropForeign('purchase_requisitions_branch_id_foreign');
        });
    }
}
