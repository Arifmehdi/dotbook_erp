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
        Schema::table('expanses', function (Blueprint $table) {
            if (! Schema::hasColumn('expanses', 'purchase_ref_id')) {
                $table->unsignedBigInteger('purchase_ref_id')->after('branch_id')->nullable();
            }

            if (! Schema::hasColumn('expanses', 'mode')) {
                $table->tinyInteger('mode')->after('voucher_no')->default(1);
            }
            if (! Schema::hasColumn('expanses', 'debit_total')) {
                $table->decimal('debit_total', 22, 2)->after('due')->default(0);
            }
            if (! Schema::hasColumn('expanses', 'credit_total')) {
                $table->decimal('credit_total', 22, 2)->after('debit_total')->default(0);
            }
            if (! Schema::hasColumn('expanses', 'is_transaction_details')) {
                $table->boolean('is_transaction_details')->after('created_by_id')->default(1);
            }
            if (! Schema::hasColumn('expanses', 'maintain_cost_centre')) {
                $table->boolean('maintain_cost_centre')->after('is_transaction_details')->default(1);
            }

            if (! Schema::hasColumn('expanses', 'purchase_ref_id')) {
                $table->foreign('purchase_ref_id')->references('id')->on('purchases')->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('expanses', function (Blueprint $table) {
            $table->dropForeign(['purchase_ref_id']);
            $table->dropColumn('purchase_ref_id');
            $table->dropColumn('mode');
            $table->dropColumn('debit_total');
            $table->dropColumn('total_credit');
            $table->dropColumn('is_transaction_details');
            $table->dropColumn('maintain_cost_centre');
        });
    }
};
