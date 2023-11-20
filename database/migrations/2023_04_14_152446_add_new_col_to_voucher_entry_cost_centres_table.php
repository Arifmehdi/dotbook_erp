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
        Schema::table('voucher_entry_cost_centres', function (Blueprint $table) {
            $table->unsignedBigInteger('expense_description_id')->after('journal_entry_id')->nullable();
            $table->foreign('expense_description_id')->references('id')->on('expense_descriptions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('voucher_entry_cost_centres', function (Blueprint $table) {
            $table->dropForeign(['expense_description_id']);
            $table->dropColumn('expense_description_id');
        });
    }
};
