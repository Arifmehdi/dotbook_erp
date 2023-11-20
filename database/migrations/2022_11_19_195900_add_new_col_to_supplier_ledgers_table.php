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
        Schema::table('supplier_ledgers', function (Blueprint $table) {

            $table->unsignedBigInteger('journal_entry_id')->after('supplier_payment_id')->nullable();
            $table->foreign('journal_entry_id')->references('id')->on('journal_entries')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('supplier_ledgers', function (Blueprint $table) {

            $table->dropForeign('supplier_ledgers_journal_entry_id_foreign');
            $table->dropColumn('journal_entry_id');
        });
    }
};
