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
        Schema::table('daily_stock_products', function (Blueprint $table) {
            $table->dropForeign(['tax_id']);
            $table->dropColumn('tax_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('daily_stock_products', function (Blueprint $table) {
            $table->unsignedBigInteger('tax_id')->after('unit_cost_exc_tax')->nullable();
            $table->foreign('tax_id')->references('id')->on('taxes')->onDelete('set null');
        });
    }
};
