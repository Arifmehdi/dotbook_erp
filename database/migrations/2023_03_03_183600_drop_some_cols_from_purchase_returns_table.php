<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchase_returns', function (Blueprint $table) {
            $table->dropForeign(['supplier_id']);
            $table->dropForeign(['purchase_return_account_id']);
            $table->dropColumn('supplier_id');
            $table->dropColumn('purchase_return_account_id');
            $table->dropColumn('invoice_id');
            $table->dropColumn('admin_id');
            $table->dropColumn('return_type');
            $table->dropColumn('total_return_due');
            $table->dropColumn('total_return_due_received');
            $table->dropColumn('purchase_tax_percent');
            $table->dropColumn('purchase_tax_amount');
            $table->dropColumn('month');
            $table->dropColumn('year');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
};
