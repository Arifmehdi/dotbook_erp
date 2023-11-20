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
        Schema::create('voucher_entry_cost_centres', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('payment_description_id')->nullable();
            $table->unsignedBigInteger('journal_entry_id')->nullable();
            $table->unsignedBigInteger('cost_centre_id')->nullable();
            $table->decimal('amount', 22, 2)->default(0);
            $table->timestamps();

            $table->foreign('payment_description_id')->references('id')->on('payment_descriptions')->onDelete('cascade');
            $table->foreign('journal_entry_id')->references('id')->on('journal_entries')->onDelete('cascade');
            $table->foreign('cost_centre_id')->references('id')->on('cost_centres')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('voucher_entry_cost_centres');
    }
};
