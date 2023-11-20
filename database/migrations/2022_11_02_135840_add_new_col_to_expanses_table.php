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
        Schema::table('expanses', function (Blueprint $table) {

            $table->string('voucher_no')->after('invoice_id')->nullable();
            $table->string('expense_account_ids')->after('category_ids')->nullable();
            $table->unsignedBigInteger('created_by_id')->after('admin_id')->nullable();

            $table->foreign('created_by_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('expanses', function (Blueprint $table) {
            //
        });
    }
};
