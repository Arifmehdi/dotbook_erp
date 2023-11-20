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
        Schema::table('accounts', function (Blueprint $table) {

            $table->unsignedBigInteger('account_group_id')->nullable();
            $table->foreign('account_group_id')->references('id')->on('account_groups')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('accounts', function (Blueprint $table) {

            $table->dropForeign('accounts_account_group_id_foreign');
            $table->dropColumn('account_group_id');
        });
    }
};
