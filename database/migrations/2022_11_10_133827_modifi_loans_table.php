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
        Schema::table('loans', function (Blueprint $table) {

            $table->dropForeign('loans_expense_id_foreign');
            $table->dropForeign('loans_loan_company_id_foreign');
            $table->dropForeign('loans_created_user_id_foreign');
            $table->dropForeign('loans_purchase_id_foreign');

            $table->dropColumn('expense_id');
            $table->dropColumn('purchase_id');
            $table->dropColumn('loan_company_id');
            $table->dropColumn('created_user_id');
            $table->dropColumn('reference_no');
            $table->dropColumn('loan_by');

            $table->renameColumn('loan_reason', 'remarks');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('loans', function (Blueprint $table) {
            //
        });
    }
};
