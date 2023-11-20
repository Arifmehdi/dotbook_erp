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
        Schema::table('loan_additional_expenses', function (Blueprint $table) {
            $table->boolean('is_delete_in_update')->after('amount')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('loan_additional_expenses', function (Blueprint $table) {
            $table->dropColumn('is_delete_in_update');
        });
    }
};
