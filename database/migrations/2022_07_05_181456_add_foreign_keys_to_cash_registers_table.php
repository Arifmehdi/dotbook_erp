<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToCashRegistersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cash_registers', function (Blueprint $table) {
            $table->foreign(['sale_account_id'])->references(['id'])->on('accounts')->onDelete('CASCADE');
            $table->foreign(['admin_id'])->references(['id'])->on('users')->onDelete('CASCADE');
            $table->foreign(['cash_counter_id'])->references(['id'])->on('cash_counters')->onDelete('SET NULL');
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
        Schema::table('cash_registers', function (Blueprint $table) {
            $table->dropForeign('cash_registers_sale_account_id_foreign');
            $table->dropForeign('cash_registers_admin_id_foreign');
            $table->dropForeign('cash_registers_cash_counter_id_foreign');
            $table->dropForeign('cash_registers_branch_id_foreign');
        });
    }
}
