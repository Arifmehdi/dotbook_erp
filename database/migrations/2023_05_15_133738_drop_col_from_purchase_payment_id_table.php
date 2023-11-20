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
            $table->dropForeign(['purchase_payment_id']);
            $table->dropColumn('purchase_payment_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('account_ledgers', function (Blueprint $table) {

        });
    }
};
