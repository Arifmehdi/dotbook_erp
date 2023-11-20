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
        Schema::table('account_branches', function (Blueprint $table) {

            $table->boolean('is_global')->after('account_id')->default(0)->comment('This column only for duties and taxes.');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('account_branches', function (Blueprint $table) {
            //
        });
    }
};
