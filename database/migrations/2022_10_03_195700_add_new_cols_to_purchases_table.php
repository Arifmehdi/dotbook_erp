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
        Schema::table('purchases', function (Blueprint $table) {
            $table->decimal('labour_cost', 22, 2)->after('report_date')->default(0);
            $table->decimal('transport_cost', 22, 2)->after('labour_cost')->default(0);
            $table->decimal('scale_charge', 22, 2)->after('transport_cost')->default(0);
            $table->decimal('others', 22, 2)->after('scale_charge')->default(0);
            $table->decimal('total_additional_expense', 22, 2)->after('others')->default(0);
            $table->decimal('total_expense_with_item', 22, 2)->after('total_additional_expense')->default(0);
            $table->unsignedBigInteger('expense_id')->after('total_expense_with_item')->nullable();
            $table->unsignedBigInteger('purchase_by_scale_id')->after('expense_id')->nullable();
            $table->foreign(['expense_id'])->references(['id'])->on('expanses')->onDelete('cascade');
            $table->foreign(['purchase_by_scale_id'])->references(['id'])->on('purchase_by_scales')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchases', function (Blueprint $table) {
            //
        });
    }
};
