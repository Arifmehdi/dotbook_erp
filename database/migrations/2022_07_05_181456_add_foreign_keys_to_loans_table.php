<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToLoansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('loans', function (Blueprint $table) {
            $table->foreign(['expense_id'])->references(['id'])->on('expanses')->onDelete('CASCADE');
            $table->foreign(['loan_company_id'])->references(['id'])->on('loan_companies')->onDelete('CASCADE');
            $table->foreign(['account_id'])->references(['id'])->on('accounts')->onDelete('CASCADE');
            $table->foreign(['created_user_id'])->references(['id'])->on('users')->onDelete('SET NULL');
            $table->foreign(['loan_account_id'])->references(['id'])->on('accounts')->onDelete('CASCADE');
            $table->foreign(['purchase_id'])->references(['id'])->on('purchases')->onDelete('CASCADE');
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
        Schema::table('loans', function (Blueprint $table) {
            $table->dropForeign('loans_expense_id_foreign');
            $table->dropForeign('loans_loan_company_id_foreign');
            $table->dropForeign('loans_account_id_foreign');
            $table->dropForeign('loans_created_user_id_foreign');
            $table->dropForeign('loans_loan_account_id_foreign');
            $table->dropForeign('loans_purchase_id_foreign');
            $table->dropForeign('loans_branch_id_foreign');
        });
    }
}
