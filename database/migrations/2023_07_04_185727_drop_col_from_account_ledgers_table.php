<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('account_ledgers', function (Blueprint $table) {
            Schema::disableForeignKeyConstraints();
            $table->dropIndex(['payroll_payment_id']);
            $table->dropForeign(['production_id']);
            $table->dropForeign(['loan_id']);
            $table->dropColumn('payroll_id');
            $table->dropColumn('payroll_payment_id');
            $table->dropColumn('production_id');
            $table->dropColumn('loan_id');
            Schema::enableForeignKeyConstraints();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('account_ledgers', function (Blueprint $table) {
            //
        });
    }
};
