<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('money_receipts', function (Blueprint $table) {
            $table->string('status')->after('month')->default('Pending');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('money_receipts', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
