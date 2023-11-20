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
        Schema::table('sale_returns', function (Blueprint $table) {
            $table->string('voucher_no', 255)->after('id')->nullable();
            $table->unsignedBigInteger('sr_user_id')->after('branch_id')->nullable();
            $table->unsignedBigInteger('created_by_id')->after('sr_user_id')->nullable();
            $table->unsignedBigInteger('sale_account_id')->after('created_by_id')->nullable();
            $table->unsignedBigInteger('tax_ac_id')->after('return_discount_amount')->nullable();
            $table->string('all_price_type', 10)->after('tax_ac_id')->nullable();

            $table->foreign('sr_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('created_by_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('sale_account_id')->references('id')->on('accounts')->onDelete('cascade');
            $table->foreign('tax_ac_id')->references('id')->on('accounts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sale_returns', function (Blueprint $table) {
            $table->dropForeign(['sr_user_id']);
            $table->dropColumn('sr_user_id');
            $table->dropForeign(['created_by_id']);
            $table->dropColumn('created_by_id');
            $table->dropForeign(['sale_account_id']);
            $table->dropColumn('sale_account_id');
            $table->dropForeign(['tax_ac_id']);
            $table->dropColumn('tax_ac_id');
            $table->dropColumn('all_price_type');
        });
    }
};
