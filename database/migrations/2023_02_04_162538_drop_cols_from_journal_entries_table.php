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
        Schema::table('journal_entries', function (Blueprint $table) {
            $table->dropForeign('journal_entries_customer_id_foreign');
            $table->dropForeign('journal_entries_assigned_user_id_foreign');
            $table->dropForeign('journal_entries_supplier_id_foreign');

            $table->dropColumn('assigned_user_id');
            $table->dropColumn('table_name');
            $table->dropColumn('customer_id');
            $table->dropColumn('supplier_id');
            $table->dropColumn('debit');
            $table->dropColumn('credit');
            $table->dropColumn('amount_type');
            $table->dropColumn('note');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('journal_entries', function (Blueprint $table) {

            $table->unsignedBigInteger('customer_id')->after('id')->nullable();
            $table->unsignedBigInteger('assigned_user_id')->after('customer_id')->nullable();
            $table->unsignedBigInteger('supplier_id')->after('assigned_user_id')->nullable();
            $table->string('table_name')->after('supplier_id')->nullable();
            $table->decimal('debit', 22, 2)->after('table_name')->nullable();
            $table->decimal('credit', 22, 2)->after('debit')->nullable();
            $table->string('amount_type')->after('credit')->nullable();
            $table->string('note')->after('amount_type')->nullable();
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('assigned_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('cascade');
        });
    }
};
