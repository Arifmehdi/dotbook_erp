<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToContrasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contras', function (Blueprint $table) {
            $table->foreign(['user_id'])->references(['id'])->on('users')->onDelete('SET NULL');
            $table->foreign(['branch_id'])->references(['id'])->on('branches')->onDelete('CASCADE');
            $table->foreign(['sender_account_id'])->references(['id'])->on('accounts')->onDelete('SET NULL');
            $table->foreign(['receiver_account_id'])->references(['id'])->on('accounts')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contras', function (Blueprint $table) {
            $table->dropForeign('contras_user_id_foreign');
            $table->dropForeign('contras_branch_id_foreign');
            $table->dropForeign('contras_sender_account_id_foreign');
            $table->dropForeign('contras_receiver_account_id_foreign');
        });
    }
}
