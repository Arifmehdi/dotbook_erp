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
        Schema::table('expense_descriptions', function (Blueprint $table) {

            $table->unsignedBigInteger('expense_account_id')->after('expense_category_id')->nullable();
            $table->foreign('expense_account_id')->references('id')->on('accounts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('expense_descriptions', function (Blueprint $table) {
            //
        });
    }
};
