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
        Schema::table('incomes', function (Blueprint $table) {
            if (Schema::hasColumns('incomes', ['category_ids', 'tax_amount', 'tax', 'net_total_amount'])) {
                $table->dropColumn('category_ids');
                $table->dropColumn('tax');
                $table->dropColumn('tax_amount');
                $table->dropColumn('net_total_amount');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('incomes', function (Blueprint $table) {
            //
        });
    }
};
