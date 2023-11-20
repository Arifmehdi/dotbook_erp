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
        Schema::table('expanse_payments', function (Blueprint $table) {
            $table->dropColumn('admin_id');
            $table->dropColumn('card_no');
            $table->dropColumn('card_holder');
            $table->dropColumn('card_type');
            $table->dropColumn('card_transaction_no');
            $table->dropColumn('card_month');
            $table->dropColumn('card_year');
            $table->dropColumn('card_secure_code');
            $table->dropColumn('account_no');
            $table->dropColumn('month');
            $table->dropColumn('year');
            $table->dropColumn('pay_mode');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
};
