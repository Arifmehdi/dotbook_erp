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
            $table->dropForeign(['customer_id']);
            $table->dropColumn('customer_id');
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
            $table->dropForeign(['sale_return_account_id']);
            $table->dropColumn('sale_return_account_id');
            $table->dropColumn('invoice_id');
            $table->dropColumn('admin_id');
            $table->dropColumn('total_return_due_pay');
            $table->dropColumn('total_return_due');
            $table->dropColumn('month');
            $table->dropColumn('year');
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

            $table->unsignedBigInteger('customer_id')->after('sale_id')->nullable();
            $table->unsignedBigInteger('user_id')->after('customer_id')->nullable();
            $table->unsignedBigInteger('sale_return_account_id')->after('user_id')->nullable();

            $table->string('invoice_id', 255)->after('id')->nullable();
            $table->unsignedBigInteger('admin_id')->after('invoice_id')->nullable();
            $table->decimal('total_return_due_pay', 22, 2)->after('total_return_amount')->nullable();
            $table->decimal('total_return_due', 22, 2)->after('total_return_due_pay')->nullable();
            $table->string('month', 20)->after('report_date')->nullable();
            $table->string('year', 20)->after('month')->nullable();

            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('sale_return_account_id')->references('id')->on('accounts')->onDelete('cascade');
        });
    }
};
