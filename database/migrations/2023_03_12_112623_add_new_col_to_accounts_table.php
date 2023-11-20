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
            $table->boolean('is_fixed')->after('created_by_id')->comment('updatable_but_not_deletable')->default(0);
            $table->boolean('is_main_capital_account')->after('is_fixed')->comment('updatable but not deletable and noticeable capital account')->default(0);
            $table->boolean('is_main_pl_account')->after('is_main_capital_account')->comment('updatable but deletable and noticeable capital account and link with net profit loss account')->default(0);
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
            $table->dropColumn('is_fixed');
            $table->dropColumn('is_main_capital_account');
            $table->dropColumn('is_main_pl_account');
        });
    }
};
