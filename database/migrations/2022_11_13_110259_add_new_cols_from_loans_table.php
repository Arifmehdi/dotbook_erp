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
        Schema::table('loans', function (Blueprint $table) {

            $table->unsignedBigInteger('tax_account_id')->after('per_installment')->nullable();
            $table->decimal('tax_percent', 22, 2)->after('tax_account_id')->default(0);
            $table->decimal('tax_amount', 22, 2)->after('tax_percent')->default(0);
            $table->decimal('total_additional_expense', 22, 2)->after('tax_percent')->default(0);
            $table->decimal('loan_amount_inc_deduction', 22, 2)->after('total_additional_expense')->default(0);

            $table->foreign('tax_account_id')->references('id')->on('accounts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('loans', function (Blueprint $table) {
            $table->dropForeign('loans_tax_account_id_foreign');

            $table->dropColumn('tax_account_id');
            $table->dropColumn('tax_percent');
            $table->dropColumn('tax_amount');
            $table->dropColumn('total_additional_expense');
            $table->dropColumn('loan_amount_inc_deduction');
        });
    }
};
