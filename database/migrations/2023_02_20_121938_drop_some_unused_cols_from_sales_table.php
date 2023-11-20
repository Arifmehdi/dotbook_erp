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
        Schema::table('sales', function (Blueprint $table) {
            $table->dropForeign(['do_to_final_by_id']);
            $table->dropColumn('do_to_final_by_id');
            $table->dropColumn('admin_id');
            $table->dropColumn('gross_pay');
            $table->dropColumn('previous_due');
            $table->dropColumn('all_total_payable');
            $table->dropColumn('previous_due_paid');
            $table->dropColumn('customer_running_balance');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sales', function (Blueprint $table) {

            $table->unsignedBigInteger('do_to_final_by_id')->after('do_by_id')->nullable();
            $table->foreign('do_to_final_by_id')->references('id')->on('users')->onDelete('set null');
            $table->unsignedBigInteger('admin_id')->after('payment_note')->nullable();
            $table->decimal('gross_pay', 22, 2)->after('attachment')->default(0);
            $table->decimal('previous_due', 22, 2)->after('gross_pay')->default(0);
            $table->decimal('all_total_payable', 22, 2)->after('previous_due')->default(0);
            $table->decimal('previous_due_paid', 22, 2)->after('all_total_payable')->default(0);
            $table->decimal('customer_running_balance', 22, 2)->after('previous_due_paid')->default(0);
        });
    }
};
