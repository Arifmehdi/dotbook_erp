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
        Schema::table('account_ledgers', function (Blueprint $table) {
            if (! Schema::hasColumn('account_ledgers', 'income_description_id')) {
                $table->unsignedBigInteger('income_description_id')->after('income_id')->nullable();
                $table->foreign('income_description_id')->references('id')->on('income_descriptions')->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('account_ledgers', function (Blueprint $table) {
            $table->dropColumn('income_description_id');
        });
    }
};
