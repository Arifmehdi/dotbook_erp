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
        Schema::table('journals', function (Blueprint $table) {
            $table->boolean('is_transaction_details')->after('created_by_id')->default(1);
            $table->boolean('maintain_cost_centre')->after('is_transaction_details')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('journal', function (Blueprint $table) {
            $table->dropColumn('is_transaction_details');
            $table->dropColumn('maintain_cost_centre');
        });
    }
};
