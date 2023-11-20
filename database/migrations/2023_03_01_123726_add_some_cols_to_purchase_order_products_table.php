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
        Schema::table('purchase_order_products', function (Blueprint $table) {
            $table->unsignedBigInteger('tax_ac_id')->after('subtotal')->nullable();
            $table->decimal('unit_tax_amount', 22, 2)->after('unit_tax_percent')->default(0);
            $table->foreign('tax_ac_id')->references('id')->on('accounts')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchase_order_products', function (Blueprint $table) {
            $table->dropForeign(['tax_ac_id']);
            $table->dropColumn('tax_ac_id');
            $table->dropColumn('unit_tax_amount');
        });
    }
};
