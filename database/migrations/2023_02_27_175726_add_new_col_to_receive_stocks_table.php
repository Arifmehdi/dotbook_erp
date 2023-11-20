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
        Schema::table('receive_stocks', function (Blueprint $table) {
            $table->unsignedBigInteger('supplier_account_id')->after('supplier_id')->nullable();
            $table->foreign('supplier_account_id')->references('id')->on('accounts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('receive_stocks', function (Blueprint $table) {
            $table->dropForeign(['supplier_account_id']);
            $table->dropColumn('supplier_account_id');
        });
    }
};
