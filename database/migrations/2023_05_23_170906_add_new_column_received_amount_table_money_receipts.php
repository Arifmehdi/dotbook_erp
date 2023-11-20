<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('money_receipts', function (Blueprint $table) {
            $table->decimal('received_amount', 22)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('money_receipts', function (Blueprint $table) {
            $table->dropForeign(['received_amount']);
        });
    }
};
