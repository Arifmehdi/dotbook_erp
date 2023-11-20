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
        Schema::table('purchase_return_products', function (Blueprint $table) {
            $table->decimal('unit_discount', 22, 2)->after('unit_cost')->default(0);
            $table->tinyInteger('unit_discount_type')->after('unit_discount')->default(1);
            $table->decimal('unit_discount_amount', 22, 2)->after('unit_discount_type')->default(0);
            $table->unsignedBigInteger('tax_ac_id')->after('unit_discount_amount')->nullable();
            $table->tinyInteger('tax_type')->after('tax_ac_id')->default(1);
            $table->decimal('unit_tax_percent', 22, 2)->after('tax_type')->default(0);
            $table->decimal('unit_tax_amount', 22, 2)->after('unit_tax_percent')->default(0);
            $table->decimal('unit_cost_inc_tax', 22, 2)->after('unit_tax_amount')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchase_return_products', function (Blueprint $table) {
            //
        });
    }
};
