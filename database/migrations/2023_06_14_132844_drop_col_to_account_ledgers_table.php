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
            $table->dropForeign(['stock_adjustment_recover_id']);
            $table->dropColumn('stock_adjustment_recover_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('account_ledgers', function (Blueprint $table) {
            $table->unsignedBigInteger('stock_adjustment_recover_id')->after('adjustment_id')->nullable();
            $table->foreign('stock_adjustment_recover_id')->references('id')->on('stock_adjustment_recovers')->onDelete('cascade');
        });
    }
};
